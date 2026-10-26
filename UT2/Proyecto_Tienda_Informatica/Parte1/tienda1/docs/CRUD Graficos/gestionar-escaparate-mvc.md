# Laravel en tienda1: gestionar escaparate

> **Lectura por capas MVC.** Cada diagrama agrupa los pasos en **C · Controlador**, **M · Modelo** y **V · Vista**. Se separan las actuaciones del navegador, las rutas y el middleware de Laravel y las operaciones de MySQL/Storage porque estos elementos no son, por sí mismos, una capa MVC. Las flechas conservan el orden y las decisiones del flujo original.

El escaparate administra fotografías independientes de las de productos. Cada registro contiene nombre, autor y ruta del archivo. El modelo se llama `Imagen`, el controlador es `ImagenController` y las rutas utilizan `/admin/imagenes`.

Laravel recibe cada petición por `public/index.php`, resuelve la ruta y ejecuta el middleware web. Eloquent trabaja con MySQL, Storage trabaja con los archivos y Blade construye el HTML que recibe el navegador.

| Petición | Acción de ImagenController | Resultado |
| --- | --- | --- |
| GET `/admin/imagenes` | `index` | Tabla de fotografías con Ver, Editar y Eliminar. |
| GET `/admin/imagenes/nueva` | `create` | Formulario de subida. |
| POST `/admin/imagenes` | `store` | Archivo guardado y registro creado. |
| GET `/admin/imagenes/{imagen}` | `show` | Detalle de la fotografía. |
| GET `/admin/imagenes/{imagen}/edit` | `edit` | Formulario con los datos y la foto actuales. |
| PUT o PATCH `/admin/imagenes/{imagen}` | `update` | Datos actualizados y fotografía sustituida si se adjunta otra. |
| DELETE `/admin/imagenes/{imagen}` | `destroy` | Registro y archivo eliminados. |

## 1. Consultar el listado y mostrar las fotos

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Navegador: GET /admin/imagenes"]
        J["Navegador recibe el HTML y solicita cada imagen"]
        N["Navegador muestra la fotografía en la tabla"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: entrada, rutas y middleware web"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        C["ImagenController.index"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        D["Eloquent: Imagen.latest.get"]
        F["Colección con nombre, autor y ruta de cada foto"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        G["Blade: admin/imagenes/index.blade.php"]
        I["Layouts admin y app: HTML con tabla y etiquetas img"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        E[("MySQL: tabla imagenes")]
        H["Storage genera la URL /storage/escaparate/archivo"]
        K["El servidor atiende la URL /storage/escaparate/archivo"]
        L["Enlace public/storage hacia storage/app/public"]
        M[("Archivo en storage/app/public/escaparate")]
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
    M --> N
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,J,N clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class C clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class D,F clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class G,I clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class E,H,K,L,M clase_INFRA;
```

La consulta a MySQL no descarga la fotografía: recupera su ruta. El navegador realiza después otra petición para obtener el archivo. `php artisan storage:link` crea el enlace público necesario. Las URLs utilizan el dominio y el puerto desde los que se abre la página.

Para consultar el detalle, `GET /admin/imagenes/{imagen}` activa Route Model Binding. Si el id no existe se responde con 404; si existe, `show` envía el modelo a `admin/imagenes/show.blade.php`.

## 2. Subir una fotografía

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/imagenes/nueva"]
        E["Navegador: nombre, autor y archivo"]
        F["POST /admin/imagenes con multipart/form-data y CSRF"]
        S["Nueva petición GET: mostrar la fotografía en la tabla"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: ruta y middleware web"]
        G["Laravel comprueba el token CSRF"]
        H{"¿Token válido?"}
        I["Rechazar petición: 419"]
        M["Volver con errores y datos anteriores; seleccionar de nuevo el archivo"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        C["ImagenController.create"]
        J["ImagenController.store llama a validar"]
        K["Nombre y autor obligatorios, máximo 120 caracteres; JPG, PNG o WebP hasta 2 MB"]
        L{"¿Datos y archivo válidos?"}
        P{"¿Se guardó el registro?"}
        Q["Eliminar el archivo nuevo y propagar la excepción"]
        R["Redirigir a /admin/imagenes"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        O["Imagen.create: guardar nombre, autor y ruta en MySQL"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        D["Blade: create y parcial _form"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        N["guardarImagen: Storage guarda el archivo en disco public, carpeta escaparate"]
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
    P -->|No| Q
    P -->|Sí| R
    R --> S
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,E,F,S clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,G,H,I,M clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class C,J,K,L,P,Q,R clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class O clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class D clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class N clase_INFRA;
```

El método privado `validar` usa `$request->validate()`: no hay un FormRequest específico para este módulo. El archivo se exige en el alta; el campo de archivo no se puede rellenar automáticamente con `old()`.

## 3. Editar y sustituir una fotografía

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["GET /admin/imagenes/id/edit"]
        G["POST multipart con _method=PUT y token CSRF"]
        V["GET /admin/imagenes: mostrar cambios"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        B["Laravel: ruta, middleware y Route Model Binding"]
        C{"¿Existe la imagen?"}
        D["Respuesta 404"]
        H["Laravel interpreta PUT y verifica CSRF"]
        K["Volver con errores; mantener registro y foto anteriores"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        E["ImagenController.edit"]
        I["ImagenController.update: validar datos; archivo opcional"]
        J{"¿Datos válidos?"}
        L{"¿Se adjuntó otra fotografía?"}
        M["Preparar solo los cambios de nombre y autor"]
        P{"¿Actualización correcta?"}
        Q["Retirar el archivo nuevo si existe y conservar el anterior; propagar excepción"]
        R{"¿Se guardó un archivo nuevo?"}
        T["Conservar el archivo actual"]
        U["Redirigir al listado"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        O["Imagen.update: actualizar registro en MySQL"]
    end
    subgraph L_VISTA["V · VISTA"]
        direction TB
        F["Blade: edit y _form con nombre, autor y foto actual"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        N["Guardar el archivo nuevo y preparar su ruta"]
        S["Storage: eliminar la fotografía anterior"]
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
    L -->|No| M
    L -->|Sí| N
    M --> O
    N --> O
    O --> P
    P -->|No| Q
    P -->|Sí| R
    R -->|Sí| S
    R -->|No| T
    S --> U
    T --> U
    U --> V
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,G,V clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class B,C,D,H,K clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class E,I,J,L,M,P,Q,R,T,U clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class O clase_MODELO;
    classDef clase_VISTA fill:#F4EAFF,stroke:#A579D0,color:#213247,stroke-width:1.5px;
    class F clase_VISTA;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class N,S clase_INFRA;
```

La sustitución utiliza dos recursos diferentes: una fila de MySQL y un archivo del disco. El controlador retira el archivo nuevo si falla la escritura del registro. Un token CSRF incorrecto se rechaza antes de ejecutar la acción.

## 4. Eliminar una fotografía

```mermaid
flowchart TD
    %% Procesos originales organizados por capas.
    %% Navegador, Laravel y servicios externos no son capas MVC.
    subgraph L_CLIENTE["NAVEGADOR / USUARIO"]
        direction TB
        A["Pulsar Eliminar en la fila de la fotografía"]
        B["POST con _method=DELETE y token CSRF"]
        L["Nueva petición GET: tabla sin la fotografía eliminada"]
    end
    subgraph L_FRAMEWORK["LARAVEL · RUTAS Y MIDDLEWARE"]
        direction TB
        C["Laravel: ruta DELETE y comprobación CSRF"]
        D["Route Model Binding: localizar Imagen"]
        E{"¿Existe el registro?"}
        F["Respuesta 404"]
    end
    subgraph L_CONTROLADOR["C · CONTROLADOR"]
        direction TB
        G["ImagenController.destroy"]
        K["Redirigir a /admin/imagenes"]
    end
    subgraph L_MODELO["M · MODELO"]
        direction TB
        H["Imagen.delete: borrar registro de MySQL"]
        I["Modelo Imagen: evento deleted"]
    end
    subgraph L_INFRA["DATOS Y ARCHIVOS · SERVICIOS"]
        direction TB
        J["Storage: eliminar el archivo del disco public"]
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
    K --> L
    %% Colores por capa arquitectónica
    classDef clase_CLIENTE fill:#E8F3FF,stroke:#518BC4,color:#213247,stroke-width:1.5px;
    class A,B,L clase_CLIENTE;
    classDef clase_FRAMEWORK fill:#F1F4F8,stroke:#8797B1,color:#213247,stroke-width:1.5px;
    class C,D,E,F clase_FRAMEWORK;
    classDef clase_CONTROLADOR fill:#E8F6EE,stroke:#54A17A,color:#213247,stroke-width:1.5px;
    class G,K clase_CONTROLADOR;
    classDef clase_MODELO fill:#FFF0D9,stroke:#D4A053,color:#213247,stroke-width:1.5px;
    class H,I clase_MODELO;
    classDef clase_INFRA fill:#F1F2F4,stroke:#8E99AA,color:#213247,stroke-width:1.5px;
    class J clase_INFRA;
```

El borrado del archivo se define en el modelo `Imagen`; el controlador solicita eliminar el registro. El botón **Volver** permite regresar al listado o al panel sin modificar los datos.

## Archivos para seguir el recorrido

- [Rutas](../../routes/web.php).
- [ImagenController](../../app/Http/Controllers/ImagenController.php).
- [Modelo Imagen y evento deleted](../../app/Models/Imagen.php).
- [Formulario compartido](../../resources/views/admin/imagenes/_form.blade.php).
- [Listado de fotografías](../../resources/views/admin/imagenes/index.blade.php).
- [Configuración de Storage](../../config/filesystems.php).
