# Usuario: guardar y editar la ficha de cliente

[Índice de diagramas](../README.md) · **Área Usuario**

La ficha es **persistente**: guarda nombre, correo, dirección, tipo y edad en `clientes`. No es una simple previsualización. Sin cliente seleccionado crea uno; con `cliente_id` actualiza ese registro.

## Abrir el formulario

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /ficha-cliente con cliente_id opcional"]
        val["Validar cliente_id si se recibe"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        create["FichaClienteController.create"]
        ret["Preparar tipos de cliente de config/tienda.php y datos actuales"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        find["Cliente: buscar el seleccionado; si no hay selección, formulario nuevo"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/fichas/create: nombre, correo, tipo, edad, dirección y @csrf"]
    end
    get --> val
    val --> create
    create --> find
    find --> db
    db --> ret
    ret --> form
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,val claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class create,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class find claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form claseV;
```

## Guardar y consultar después

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/fichas/create: enviar campos"]
        error["Volver al formulario con errores y valores anteriores"]
        show["usuario/fichas/show: ficha guardada, Editar e Ir al carrito"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        post["POST /ficha-cliente con CSRF"]
        valid["GuardarClienteRequest: validar campos y cliente_id"]
        ok{"¿Datos válidos?"}
        get["GET /ficha-cliente/id: binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        store["FichaClienteController.store: usar validated y retirar cliente_id de los campos"]
        which{"¿Se ha seleccionado un cliente?"}
        redirect["302 a fichas.show con cliente_id"]
        read["FichaClienteController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        update["Cliente.update: guardar ficha del seleccionado"]
        create["Cliente.create: nuevo cliente"]
        load["Cliente: consultar el registro persistido"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes; edad como entero")]
    end
    form --> post
    post --> valid
    valid --> ok
    ok -->|No| error
    ok -->|Sí| store
    store --> which
    which -->|Sí| update
    which -->|No| create
    update --> db
    create --> db
    db --> redirect
    redirect --> get
    get --> load
    load --> read
    read --> show
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,error,show claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class post,valid,ok,get claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class store,which,redirect,read claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class update,create,load claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

El redirect después del POST permite recargar el detalle sin volver a enviar el formulario. Los clientes anteriores pueden tener tipo y edad sin indicar; el formulario público los exige para guardar su ficha. [La administración de clientes](../admin/clientes.md) permite gestionar los mismos registros.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controladores:** [FichaClienteController](../../app/Http/Controllers/FichaClienteController.php).
- **Modelos:** [Cliente](../../app/Models/Cliente.php).
- **Vistas Blade:** [usuario/fichas/create](../../resources/views/usuario/fichas/create.blade.php) · [usuario/fichas/show](../../resources/views/usuario/fichas/show.blade.php).
- **Servicios, reglas y configuración:** [Validación de la ficha](../../app/Http/Requests/GuardarClienteRequest.php) · [Tipos y opciones de la tienda](../../config/tienda.php).
- **Layout público:** [Layout de Usuario](../../resources/views/layouts/usuario.blade.php) · [Layout general](../../resources/views/layouts/app.blade.php).
