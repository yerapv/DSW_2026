# Admin: gestionar clientes

[Índice de diagramas](../README.md) · **Área Admin**

Los diagramas describen `ClienteController`, el modelo `Cliente` y las vistas `resources/views/admin/clientes` de **tienda2**. Las tablas de gestión tienen cinco registros por página y paginación numerada. Las rutas de edición aceptan PUT o PATCH; los formularios HTML usan POST con `@method('PUT')` y `@csrf` en líneas separadas.

| Petición | Método del controlador |
| --- | --- |
| GET `/admin/clientes` | `index` |
| GET `/admin/clientes/create` | `create` |
| POST `/admin/clientes` | `store` |
| GET `/admin/clientes/{cliente}` | `show` |
| GET `/admin/clientes/{cliente}/edit` | `edit` |
| PUT/PATCH `/admin/clientes/{cliente}` | `update` |
| DELETE `/admin/clientes/{cliente}` | `destroy` |

## 1. Listar y consultar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /admin/clientes"]
        b["GET /admin/clientes/id · Route Model Binding"]
        no["No existe: respuesta 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        c["ClienteController.index"]
        ret["Preparar datos del listado"]
        show["ClienteController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["Cliente: Ordenar registros y paginate(5)"]
        find["Cliente: buscar por id"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes")]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        list["admin/clientes/index.blade.php · tabla y acciones"]
        detail["admin/clientes/show.blade.php · detalle y Volver"]
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
        get["GET /admin/clientes/create"]
        post["POST /admin/clientes · CSRF"]
        csrf["Protección CSRF rechazada: 419"]
        validation["Validar nombre, email y dirección; tipo y edad opcionales"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        create["ClienteController.create"]
        store["ClienteController.store: usar únicamente los datos validados"]
        redir["302 a /admin/clientes; nueva petición GET"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/clientes/create.blade.php + _form · @csrf"]
        error["Formulario con errores y valores anteriores"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        insert["Cliente.create con datos validados"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes")]
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
    store --> insert
    insert -->|Guardar| db
    db --> redir
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,post,csrf,validation,valid claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class create,store,redir claseC;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,error claseV;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class insert claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

## 3. Editar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /admin/clientes/id/edit · binding o 404"]
        put["PUT/PATCH /admin/clientes/id · CSRF y validación"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        edit["ClienteController.edit"]
        update["ClienteController.update: usar únicamente los datos validados"]
        redir["302 a /admin/clientes"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["Cliente: datos actuales"]
        save["Cliente.update"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/clientes/edit.blade.php + _form · POST, @csrf y @method"]
        err["Mostrar errores; conservar campos"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes")]
    end
    get --> load
    load --> edit
    edit --> form
    form -->|Guardar| put
    put --> valid
    valid -->|No| err
    err --> form
    valid -->|Sí| update
    update --> save
    save --> db
    db --> redir
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,put,valid claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class edit,update,redir claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class load,save claseM;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,err claseV;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

## 4. Eliminar

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        btn["Tabla o detalle de clientes: formulario Eliminar con @csrf y @method DELETE"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        delete["DELETE /admin/clientes/id · CSRF y binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        destroy["ClienteController.destroy"]
        redir["302 a /admin/clientes"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        del["Cliente.delete"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: eliminar registro; aplicar claves foráneas")]
    end
    btn --> delete
    delete --> destroy
    destroy --> del
    del --> db
    db --> redir
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class btn claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class delete claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class destroy,redir claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class del claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

Al borrar un cliente, MySQL elimina su carrito y las líneas del carrito por cascada. Los pedidos y presupuestos permanecen: su cliente_id pasa a NULL y sus instantáneas conservan los datos guardados. Esta gestión de Admin es distinta de la ficha pública persistente.
