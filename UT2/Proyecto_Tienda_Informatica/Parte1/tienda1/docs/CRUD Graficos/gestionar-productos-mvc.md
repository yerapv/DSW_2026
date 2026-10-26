# Laravel en tienda1: gestionar productos

> **Lectura por capas MVC.** Cada diagrama agrupa los pasos en **C · Controlador**, **M · Modelo** y **V · Vista**. Se separan las actuaciones del navegador, las rutas y el middleware de Laravel y las operaciones de MySQL/Storage porque estos elementos no son, por sí mismos, una capa MVC. Las flechas conservan el orden y las decisiones del flujo original.

Estos diagramas describen el código de `tienda1`. Cada petición entra por `public/index.php`, Laravel carga la aplicación y busca la ruta en `routes/web.php`. El middleware web prepara la sesión y comprueba CSRF en los formularios de escritura. No se requiere inicio de sesión.

El controlador organiza la operación, Eloquent consulta o modifica MySQL y Blade genera el HTML. Los archivos de las fotografías se guardan en disco; MySQL conserva sus rutas.

| Petición | Acción de ProductoController | Resultado |
| --- | --- | --- |
| GET `/admin/productos` | `index` | Tabla con búsqueda, filtro y paginación. |
| GET `/admin/productos/create` | `create` | Formulario de alta y categorías disponibles. |
| POST `/admin/productos` | `store` | Producto guardado y redirección al listado. |
| GET `/admin/productos/{producto}` | `show` | Detalle del producto. |
| GET `/admin/productos/{producto}/edit` | `edit` | Formulario con los datos actuales. |
| PUT o PATCH `/admin/productos/{producto}` | `update` | Producto actualizado y redirección al listado. |
| DELETE `/admin/productos/{producto}` | `destroy` | Producto y fotografía eliminados. |

## 1. Consultar el listado y un producto

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Navegador: abrir gestión de productos"]
        B["GET /admin/productos"]
        J["Navegador: mostrar el listado"]
        K["Pulsar Ver: GET /admin/productos/id"]
        Q["Navegador: mostrar ficha y botón Volver al listado"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        C["Laravel: entrada, rutas y middleware web"]
        L["Route Model Binding: buscar Producto por id en MySQL"]
        M{"¿Existe el producto?"}
        N["Laravel responde con 404"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        D["ProductoController.index"]
        O["ProductoController.show: cargar categoría"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        E["Eloquent: buscar por nombre, filtrar por tipo y ordenar"]
        G["Cargar la categoría de cada producto y paginar de cinco en cinco"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        H["Blade: admin/productos/index.blade.php"]
        I["Layouts admin y app: HTML con tabla, acciones y Volver"]
        P["Blade: admin/productos/show.blade.php"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        F[("MySQL: productos y categorías")]
    end
    A --> B
    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H
    H --> I
    I --> J
    J --> K
    K --> L
    L --> M
    M -->|No| N
    M -->|Sí| O
    O --> P
    P --> Q
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,B,J,K,Q clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class C,L,M,N clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class D,O clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class E,G clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class H,I,P clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class F clase_INFRA;
```

`with('categoria')` permite obtener las categorías sin hacer una consulta por cada fila. `withQueryString()` conserva los filtros al cambiar de página.

## 2. Crear un producto

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/productos/create"]
        F["Navegador: completar campos y adjuntar fotografía"]
        G["POST /admin/productos con CSRF y multipart/form-data"]
        W["Nueva petición GET: consultar y mostrar la tabla actualizada"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: rutas y middleware web"]
        H["Laravel: verificar CSRF"]
        I{"¿Token válido?"}
        J["Rechazar petición: 419"]
        N["Redirigir al formulario con errores y valores anteriores"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        C["ProductoController.create"]
        K["GuardarProductoRequest: preparar la petición"]
        L["Normalizar checkbox; autorizar; validar campos y precio con coma o punto"]
        M{"¿Datos válidos?"}
        O["ProductoController.store: obtener los datos validados"]
        P["datosProducto: convertir euros a céntimos y preparar características JSON"]
        T{"¿Se guardó correctamente?"}
        U["Eliminar la foto nueva y propagar la excepción a Laravel"]
        V["Respuesta 302: redirigir a /admin/productos"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        D["Categoria: consultar las categorías en MySQL"]
        S["Producto.create: guardar datos y ruta de foto en MySQL"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        E["Blade: create y parcial _form"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        R["Storage: guardar fotografía en disco public, carpeta productos"]
    end
    A --> B
    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H
    H --> I
    I -->|No| J
    I -->|Sí| K
    K --> L
    L --> M
    M -->|No| N
    N --> E
    M -->|Sí| O
    O --> P
    P --> R
    R --> S
    S --> T
    T -->|No| U
    T -->|Sí| V
    V --> W
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,F,G,W clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,H,I,J,N clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class C,K,L,M,O,P,T,U,V clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class D,S clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class E clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class R clase_INFRA;
```

`GuardarProductoRequest` se ejecuta antes de entrar en `store`. La normalización y las reglas forman parte de esa preparación. Si la validación falla, el controlador no crea el producto ni guarda el archivo.

El usuario introduce, por ejemplo, `24,50`. El controlador guarda `2450` en `precio_centimos`. El tipo se convierte mediante `TipoProducto` y las características se almacenan como JSON.

## 3. Editar un producto

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/productos/id/edit"]
        G["Formulario POST con _method=PUT y token CSRF"]
        V["GET /admin/productos: mostrar cambios"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: ruta, middleware y Route Model Binding"]
        C{"¿Existe el producto?"}
        D["Respuesta 404"]
        H["Laravel interpreta PUT y verifica CSRF"]
        K["Volver al formulario con errores y old"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        E["ProductoController.edit: producto y categorías"]
        I["GuardarProductoRequest: validar los cambios"]
        J{"¿Datos válidos?"}
        L["ProductoController.update: convertir precio y preparar JSON"]
        M{"¿Se adjuntó otra foto?"}
        O["Conservar la ruta y el archivo actuales"]
        Q{"¿Se actualizó correctamente?"}
        R["Conservar foto anterior, retirar nueva si la hay y propagar excepción"]
        S{"¿Hay una foto nueva?"}
        U["Redirigir al listado"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        P["Producto.update: actualizar MySQL"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        F["Blade: edit y _form con valores actuales y foto"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        N["Guardar foto nueva y preparar su ruta"]
        T["Storage: eliminar la fotografía anterior"]
    end
    A --> B
    B --> C
    C -->|No| D
    C -->|Sí| E
    E --> F
    F --> G
    G --> H
    H --> I
    I --> J
    J -->|No| K
    K --> F
    J -->|Sí| L
    L --> M
    M -->|Sí| N
    M -->|No| O
    N --> P
    O --> P
    P --> Q
    Q -->|No| R
    Q -->|Sí| S
    S -->|Sí| T
    S -->|No| U
    T --> U
    U --> V
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,G,V clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,C,D,H,K clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class E,I,J,L,M,O,Q,R,S,U clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class P clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class F clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class N,T clase_INFRA;
```

La fotografía es opcional en edición. La anterior se elimina después de guardar correctamente la sustitución. La rama de validación presupone que el middleware ha aceptado el token CSRF; un token incorrecto se rechaza antes del controlador.

## 4. Eliminar un producto

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Pulsar Eliminar en la fila del producto"]
        B["POST con _method=DELETE y token CSRF"]
        M["GET del listado: mostrar las filas restantes"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        C["Laravel: ruta DELETE y comprobación CSRF"]
        D["Route Model Binding: localizar Producto"]
        E{"¿Existe?"}
        F["Respuesta 404"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        G["ProductoController.destroy"]
        L["Redirigir a /admin/productos"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        H["Producto.delete: eliminar registro en MySQL"]
        I["Modelo Producto: evento deleted"]
        J{"¿Tenía una fotografía?"}
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        K["Storage: eliminar archivo del disco public"]
    end
    A --> B
    B --> C
    C --> D
    D --> E
    E -->|No| F
    E -->|Sí| G
    G --> H
    H --> I
    I --> J
    J -->|Sí| K
    J -->|No| L
    K --> L
    L --> M
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,B,M clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class C,D,E,F clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class G,L clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class H,I,J clase_MODELO;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class K clase_INFRA;
```

Esta es la administración de `tienda1`: no intervienen clientes, carrito ni pedidos. El botón **Volver** navega al listado o al panel; no guarda ni elimina datos.

## Archivos para seguir el recorrido

- [Rutas](../../routes/web.php).
- [ProductoController](../../app/Http/Controllers/ProductoController.php).
- [GuardarProductoRequest](../../app/Http/Requests/GuardarProductoRequest.php).
- [Producto](../../app/Models/Producto.php) y [Categoria](../../app/Models/Categoria.php).
- [Formulario compartido](../../resources/views/admin/productos/_form.blade.php).
- [Layout administrativo](../../resources/views/layouts/admin.blade.php).
