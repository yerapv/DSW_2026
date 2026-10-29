# Admin: gestionar productos

[Índice de diagramas](../README.md) · **Área Admin**

Los diagramas describen `ProductoController`, el modelo `Producto` y las vistas `resources/views/admin/productos` de **tienda2**. Las tablas de gestión tienen cinco registros por página y paginación numerada. Las rutas de edición aceptan PUT o PATCH; los formularios HTML usan POST con `@method('PUT')` y `@csrf` en líneas separadas.

| Petición | Método del controlador |
| --- | --- |
| GET `/admin/productos` | `index` |
| GET `/admin/productos/create` | `create` |
| POST `/admin/productos` | `store` |
| GET `/admin/productos/{producto}` | `show` |
| GET `/admin/productos/{producto}/edit` | `edit` |
| PUT/PATCH `/admin/productos/{producto}` | `update` |
| DELETE `/admin/productos/{producto}` | `destroy` |

## 1. Listar y consultar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /admin/productos"]
        b["GET /admin/productos/id · Route Model Binding"]
        no["No existe: respuesta 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        c["ProductoController.index"]
        ret["Preparar datos del listado"]
        show["ProductoController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["Producto: Buscar por nombre, filtrar por TipoProducto y cargar categoría; paginate(5)"]
        find["Producto: buscar por id"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: productos y categorias")]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        list["admin/productos/index.blade.php · tabla y acciones"]
        detail["admin/productos/show.blade.php · detalle y Volver"]
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
        get["GET /admin/productos/create"]
        post["POST /admin/productos · CSRF"]
        csrf["Protección CSRF rechazada: 419"]
        validation["Validar GuardarProductoRequest: campos, fotografía y TipoProducto"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        create["ProductoController.create"]
        store["ProductoController.store: convertir euros con coma o punto a céntimos; preparar características JSON"]
        redir["302 a /admin/productos; nueva petición GET"]
        clean["Si falla la escritura en BD: retirar foto nueva y propagar error"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/productos/create.blade.php + _form · @csrf"]
        error["Formulario con errores y valores anteriores"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        insert["Producto.create con datos validados"]
        cat["Categoria: cargar opciones"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: productos y categorias")]
        file["Storage public: guardar foto nueva"]
    end
    get --> create
    form -->|Enviar| post
    post -->|Rechazo| csrf
    post -->|Aceptada| validation
    validation --> valid
    valid -->|No| error
    error --> form
    valid -->|Sí| store
    create --> cat
    cat --> form
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
    class insert,cat claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
```

## 3. Editar

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /admin/productos/id/edit · binding o 404"]
        put["PUT/PATCH /admin/productos/id · CSRF y validación"]
        valid{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        edit["ProductoController.edit"]
        update["ProductoController.update: convertir euros con coma o punto a céntimos; preparar características JSON"]
        redir["302 a /admin/productos"]
        new{"¿Se adjunta foto nueva?"}
        clean["Fallo en BD: borrar foto nueva y conservar la anterior"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["Producto: datos actuales"]
        save["Producto.update"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/productos/edit.blade.php + _form · POST, @csrf y @method"]
        err["Mostrar errores; conservar campos"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: productos y categorias")]
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
        btn["Tabla o detalle de productos: formulario Eliminar con @csrf y @method DELETE"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        delete["DELETE /admin/productos/id · CSRF y binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        destroy["ProductoController.destroy"]
        redir["302 a /admin/productos"]
        used{"¿Figura en un carrito?"}
        error["Volver con error; conservar producto"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        del["Producto.delete"]
        check["LineaCarrito: comprobar si el producto aparece en algún carrito"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: eliminar registro; aplicar claves foráneas")]
        file["Evento deleted: Storage borra la fotografía asociada"]
    end
    btn --> delete
    delete --> destroy
    destroy --> check
    check --> used
    used -->|Sí| error
    used -->|No| del
    del --> db
    db --> file
    file --> redir
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class btn claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class delete claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class destroy,redir,used,error claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class del,check claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
```

El tipo se guarda con el enum entero `TipoProducto`: 1 portátil, 2 periférico y 3 componente. Los presupuestos conservan sus datos históricos aunque se elimine el producto.

Las fotos son archivos del disco `public`; MySQL guarda sus rutas. Si el archivo de ejemplo no existe, la vista de productos muestra un marcador.
