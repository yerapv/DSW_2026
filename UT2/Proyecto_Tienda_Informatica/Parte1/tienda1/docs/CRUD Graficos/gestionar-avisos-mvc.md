# Laravel en tienda1: gestionar avisos

> **Lectura por capas MVC.** Cada diagrama agrupa los pasos en **C · Controlador**, **M · Modelo** y **V · Vista**. Se separan las actuaciones del navegador, las rutas y el middleware de Laravel y las operaciones de MySQL/Storage porque estos elementos no son, por sí mismos, una capa MVC. Las flechas conservan el orden y las decisiones del flujo original.

Este módulo gestiona los avisos del tablón mediante un CRUD. La petición entra por `public/index.php`, Laravel resuelve la ruta y ejecuta el middleware web antes de llamar a `AvisoController`. El modelo `Aviso` representa la tabla `avisos` de MySQL y Blade genera las pantallas.

La validación se hace en el propio controlador mediante su método privado `validar`, compartido por el alta y la edición. Este módulo no utiliza un FormRequest ni gestiona archivos.

| Petición | Acción de AvisoController | Resultado |
| --- | --- | --- |
| GET `/admin/avisos` | `index` | Tabla de avisos, del más reciente al más antiguo. |
| GET `/admin/avisos/nuevo` | `create` | Formulario de alta. |
| POST `/admin/avisos` | `store` | Aviso creado y redirección al listado. |
| GET `/admin/avisos/{aviso}` | `show` | Detalle del aviso. |
| GET `/admin/avisos/{aviso}/edit` | `edit` | Formulario con el aviso actual. |
| PUT o PATCH `/admin/avisos/{aviso}` | `update` | Aviso actualizado y redirección al listado. |
| DELETE `/admin/avisos/{aviso}` | `destroy` | Aviso eliminado y redirección al listado. |

## 1. Listar y consultar

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Navegador: GET /admin/avisos"]
        I["Navegador: mostrar tabla"]
        J["Pulsar Ver: GET /admin/avisos/id"]
        P["Navegador: mostrar contenido del aviso"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: entrada, rutas y middleware web"]
        K["Route Model Binding: buscar Aviso por id"]
        L{"¿Existe el aviso?"}
        M["Laravel responde con 404"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        C["AvisoController.index"]
        N["AvisoController.show"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        D["Eloquent: Aviso.latest.get"]
        F["Colección de avisos ordenados por fecha descendente"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        G["Blade: admin/avisos/index.blade.php"]
        H["Layouts admin y app: HTML con tabla, acciones y Volver"]
        O["Blade: admin/avisos/show.blade.php"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        E[("MySQL: tabla avisos")]
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
    L -->|No| M
    L -->|Sí| N
    N --> O
    O --> P
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,I,J,P clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,K,L,M clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class C,N clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class D,F clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class G,H,O clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class E clase_INFRA;
```

`index` consulta MySQL; `show` recibe el modelo que Laravel ha encontrado mediante el id de la URL. La vista presenta datos y no ejecuta la consulta.

## 2. Crear un aviso

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/avisos/nuevo"]
        E["Navegador: escribir título y contenido"]
        F["POST /admin/avisos con token CSRF"]
        Q["Nueva petición GET: volver a consultar y mostrar el listado"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: ruta y middleware web"]
        G["Laravel: comprobar CSRF"]
        H{"¿Token válido?"}
        I["Rechazar petición: 419"]
        M["Laravel redirige al formulario con errores y valores anteriores"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        C["AvisoController.create"]
        J["AvisoController.store"]
        K["validar: título obligatorio de hasta 120 caracteres y contenido obligatorio"]
        L{"¿Datos válidos?"}
        P["Respuesta 302: redirigir a /admin/avisos"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        N["Aviso.create con los datos validados"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        D["Blade: create y parcial _form"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        O[("INSERT en MySQL: avisos")]
    end
    A --> B
    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H
    H -->|No| I
    H -->|Sí| J
    J --> K
    K --> L
    L -->|No| M
    M --> D
    L -->|Sí| N
    N --> O
    O --> P
    P --> Q
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,E,F,Q clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,G,H,I,M clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class C,J,K,L,P clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class N clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class D clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class O clase_INFRA;
```

La redirección provoca una petición nueva. Así, recargar el listado no vuelve a enviar el formulario de alta. No se utiliza `session('ok')` para mostrar mensajes de éxito.

## 3. Editar un aviso

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/avisos/id/edit"]
        G["POST con _method=PUT y token CSRF"]
        P["GET /admin/avisos: mostrar el aviso actualizado"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: rutas, middleware y Route Model Binding"]
        C{"¿Existe el aviso?"}
        D["Respuesta 404"]
        H["Laravel interpreta PUT y verifica CSRF"]
        L["Volver al formulario con errores y old"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        E["AvisoController.edit"]
        I["AvisoController.update"]
        J["validar: mismas reglas del alta"]
        K{"¿Datos válidos?"}
        O["Redirigir al listado"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        M["Aviso.update con los datos validados"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        F["Blade: edit y _form con título y contenido actuales"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        N[("UPDATE en MySQL: avisos")]
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
    J --> K
    K -->|No| L
    L --> F
    K -->|Sí| M
    M --> N
    N --> O
    O --> P
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,G,P clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,C,D,H,L clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class E,I,J,K,O clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class M clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class F clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class N clase_INFRA;
```

El parcial `_form` se comparte entre alta y edición. `old()` recupera lo escrito si falla la validación; cuando no hay valores anteriores, la edición utiliza los del modelo. Un token CSRF incorrecto se rechaza antes de ejecutar la acción.

## 4. Eliminar un aviso

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Pulsar Eliminar en la fila del aviso"]
        B["POST con _method=DELETE y token CSRF"]
        K["Nueva petición GET: tabla sin el aviso eliminado"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        C["Laravel: ruta DELETE y comprobación CSRF"]
        D["Route Model Binding: buscar Aviso por id"]
        E{"¿Existe?"}
        F["Respuesta 404"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        G["AvisoController.destroy"]
        J["Redirigir a /admin/avisos"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        H["Aviso.delete"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        I[("DELETE en MySQL: avisos")]
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
    J --> K
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,B,K clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class C,D,E,F clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class G,J clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class H clase_MODELO;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class I clase_INFRA;
```

El botón **Volver** de las altas, los detalles y las ediciones lleva al listado. El del listado lleva al panel de administración. Es navegación y no modifica la base de datos.

## Archivos para seguir el recorrido

- [Rutas](../../routes/web.php).
- [AvisoController](../../app/Http/Controllers/AvisoController.php).
- [Modelo Aviso](../../app/Models/Aviso.php).
- [Listado](../../resources/views/admin/avisos/index.blade.php).
- [Formulario compartido](../../resources/views/admin/avisos/_form.blade.php).
- [Layout administrativo](../../resources/views/layouts/admin.blade.php).
