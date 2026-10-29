# Admin: gestionar métodos de pago

[Índice de diagramas](../README.md) · **Área Admin**

Los diagramas describen `MetodoPagoController`, el modelo `MetodoPago` y las vistas `resources/views/admin/metodos` de **tienda2**. Las tablas de gestión tienen cinco registros por página y paginación numerada. Las rutas de edición aceptan PUT o PATCH; los formularios HTML usan POST con `@method('PUT')` y `@csrf` en líneas separadas.

| Petición | Método del controlador |
| --- | --- |
| GET `/admin/metodos-pago` | `index` |
| GET `/admin/metodos-pago/create` | `create` |
| POST `/admin/metodos-pago` | `store` |
| GET `/admin/metodos-pago/{metodo}` | `show` |
| GET `/admin/metodos-pago/{metodo}/edit` | `edit` |
| PUT/PATCH `/admin/metodos-pago/{metodo}` | `update` |
| DELETE `/admin/metodos-pago/{metodo}` | `destroy` |

## 1. Listar y consultar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /admin/metodos-pago"]
        b["GET /admin/metodos-pago/id · Route Model Binding"]
        no["No existe: respuesta 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        c["MetodoPagoController.index"]
        ret["Preparar datos del listado"]
        show["MetodoPagoController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["MetodoPago: Ordenar registros y paginate(5)"]
        find["MetodoPago: buscar por id"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: metodos_pago")]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        list["admin/metodos/index.blade.php · tabla y acciones"]
        detail["admin/metodos/show.blade.php · detalle y Volver"]
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
        get["GET /admin/metodos-pago/create"]
        post["POST /admin/metodos-pago · CSRF"]
        csrf["Protección CSRF rechazada: 419"]
        validation["Validar nombre, código único y formato del código; normalizar activo"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        create["MetodoPagoController.create"]
        store["MetodoPagoController.store: guardar nombre, código y disponibilidad"]
        redir["302 a /admin/metodos-pago; nueva petición GET"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/metodos/create.blade.php + _form · @csrf"]
        error["Formulario con errores y valores anteriores"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        insert["MetodoPago.create con datos validados"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: metodos_pago")]
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
        get["GET /admin/metodos-pago/id/edit · binding o 404"]
        put["PUT/PATCH /admin/metodos-pago/id · CSRF y validación"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        edit["MetodoPagoController.edit"]
        update["MetodoPagoController.update: guardar nombre, código y disponibilidad"]
        redir["302 a /admin/metodos-pago"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["MetodoPago: datos actuales"]
        save["MetodoPago.update"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/metodos/edit.blade.php + _form · POST, @csrf y @method"]
        err["Mostrar errores; conservar campos"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: metodos_pago")]
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
        btn["Tabla o detalle de metodos: formulario Eliminar con @csrf y @method DELETE"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        delete["DELETE /admin/metodos-pago/id · CSRF y binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        destroy["MetodoPagoController.destroy"]
        redir["302 a /admin/metodos-pago"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        del["MetodoPago.delete"]
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

Un método inactivo no puede elegirse al pagar. La validación de edición ignora el propio registro al comprobar el código único. Al borrarlo, pedidos.metodo_pago_id pasa a NULL; el código y el nombre copiados en el pedido se conservan.
