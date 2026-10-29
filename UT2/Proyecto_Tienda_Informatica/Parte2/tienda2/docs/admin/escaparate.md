# Admin: gestionar el escaparate

[Índice de diagramas](../README.md) · **Área Admin**

Los diagramas describen `ImagenController`, el modelo `Imagen` y las vistas `resources/views/admin/imagenes` de **tienda2**. Las tablas de gestión tienen cinco registros por página y paginación numerada. Las rutas de edición aceptan PUT o PATCH; los formularios HTML usan POST con `@method('PUT')` y `@csrf` en líneas separadas.

| Petición | Método del controlador |
| --- | --- |
| GET `/admin/imagenes` | `index` |
| GET `/admin/imagenes/create` | `create` |
| POST `/admin/imagenes` | `store` |
| GET `/admin/imagenes/{imagen}` | `show` |
| GET `/admin/imagenes/{imagen}/edit` | `edit` |
| PUT/PATCH `/admin/imagenes/{imagen}` | `update` |
| DELETE `/admin/imagenes/{imagen}` | `destroy` |

## 1. Listar y consultar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /admin/imagenes"]
        b["GET /admin/imagenes/id · Route Model Binding"]
        no["No existe: respuesta 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        c["ImagenController.index"]
        ret["Preparar datos del listado"]
        show["ImagenController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["Imagen: Ordenar registros y paginate(5)"]
        find["Imagen: buscar por id"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: escaparate_imagenes")]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        list["admin/imagenes/index.blade.php · tabla y acciones"]
        detail["admin/imagenes/show.blade.php · detalle y Volver"]
    end
    r --> c
    c --> m
    m --> db
    db --> ret
    ret --> list
    list -->|Ver| b
    b --> find
    find -->|No encontrado| no
    find -->|Encontrado| show
    show --> detail
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r,b,no claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,ret,show claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class m,find claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class list,detail claseV;
```

## 2. Crear

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /admin/imagenes/create"]
        post["POST /admin/imagenes · CSRF"]
        csrf["Protección CSRF rechazada: 419"]
        validation["Validar nombre y autor obligatorios; archivo de imagen válido"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        create["ImagenController.create"]
        store["ImagenController.store: preparar nombre, autor y ruta del archivo"]
        redir["302 a /admin/imagenes; nueva petición GET"]
        clean["Si falla la escritura en BD: retirar foto nueva y propagar error"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/imagenes/create.blade.php + _form · @csrf"]
        error["Formulario con errores y valores anteriores"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        insert["Imagen.create con datos validados"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: escaparate_imagenes")]
        file["Storage public: guardar foto nueva"]
    end
    get --> create
    create --> form
    form -->|Enviar| post
    post -->|Rechazo| csrf
    post -->|Aceptada| validation
    validation --> valid
    valid -->|No| error
    error --> form
    valid -->|Sí| store
    store --> file
    file --> insert
    insert -->|Excepción| clean
    insert -->|Guardar| db
    db --> redir
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,post,csrf,validation,valid claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class create,store,redir,clean claseC;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,error claseV;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class insert claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
```

## 3. Editar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /admin/imagenes/id/edit · binding o 404"]
        put["PUT/PATCH /admin/imagenes/id · CSRF y validación"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        edit["ImagenController.edit"]
        update["ImagenController.update: preparar nombre, autor y ruta del archivo"]
        redir["302 a /admin/imagenes"]
        new{"¿Se adjunta foto nueva?"}
        clean["Fallo en BD: borrar foto nueva y conservar la anterior"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["Imagen: datos actuales"]
        save["Imagen.update"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/imagenes/edit.blade.php + _form · POST, @csrf y @method"]
        err["Mostrar errores; conservar campos"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: escaparate_imagenes")]
        file["Storage: guardar foto nueva"]
        old["Tras guardar en BD: borrar foto anterior si fue sustituida"]
    end
    get --> load
    load --> edit
    edit --> form
    form -->|Guardar| put
    put --> valid
    valid -->|No| err
    err --> form
    valid -->|Sí| update
    update --> new
    new -->|No: conservar ruta| save
    new -->|Sí| file
    file --> save
    save -->|Excepción| clean
    save -->|Guardar| db
    db --> old
    old --> redir
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,put,valid claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class edit,update,redir,new,clean claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class load,save claseM;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,err claseV;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file,old claseD;
```

## 4. Eliminar

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        btn["Tabla o detalle de imagenes: formulario Eliminar con @csrf y @method DELETE"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        delete["DELETE /admin/imagenes/id · CSRF y binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        destroy["ImagenController.destroy"]
        redir["302 a /admin/imagenes"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        del["Imagen.delete"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: eliminar registro; aplicar claves foráneas")]
        file["Evento deleted: Storage borra la fotografía asociada"]
    end
    btn --> delete
    delete --> destroy
    destroy --> del
    del --> db
    db --> file
    file --> redir
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class btn claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class delete claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class destroy,redir claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class del claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
```

El modelo sigue llamándose `Imagen` y declara explícitamente la tabla `escaparate_imagenes`. La galería pública solo consulta sus registros.

Las fotos son archivos del disco `public`; MySQL guarda sus rutas. Si el archivo de ejemplo no existe, la vista de productos muestra un marcador.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controlador:** [ImagenController](../../app/Http/Controllers/ImagenController.php).
- **Modelos:** [Imagen](../../app/Models/Imagen.php).
- **Vistas Blade:** [index](../../resources/views/admin/imagenes/index.blade.php) · [show](../../resources/views/admin/imagenes/show.blade.php) · [create](../../resources/views/admin/imagenes/create.blade.php) · [edit](../../resources/views/admin/imagenes/edit.blade.php) · [_form](../../resources/views/admin/imagenes/_form.blade.php).
- **Validación y soporte:** [Configuración del almacenamiento](../../config/filesystems.php).
- **Layouts:** [Layout general](../../resources/views/layouts/app.blade.php) · [Layout administrativo](../../resources/views/layouts/admin.blade.php).
