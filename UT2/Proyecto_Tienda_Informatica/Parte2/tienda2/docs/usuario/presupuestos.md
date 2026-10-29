# Usuario: generar y consultar presupuestos históricos

[Índice de diagramas](../README.md) · **Área Usuario**

El presupuesto pertenece al cliente del carrito y contiene líneas relacionadas con productos. Generarlo conserva el carrito y no efectúa un pago.

## Generación en una única transacción

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/carrito/index: Generar presupuesto con cliente_id y @csrf"]
        error["Volver al carrito con mensaje de error"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        post["POST /carrito/presupuesto · CSRF; validar cliente existente"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        quote["CarritoController.quote: localizar carrito del cliente"]
        redirect["302 a /carrito/presupuestos/id con cliente_id"]
    end
    subgraph S_CAPA["SERVICIOS Y DOMINIO · APOYO A MVC"]
        service["ServicioPresupuestos.generar: iniciar DB::transaction"]
        valid{"¿Hay propietario y al menos una línea?"}
        copy["Copiar datos del cliente; producto_id, nombre, cantidad e importe de cada línea"]
        commit["COMMIT: devolver Presupuesto"]
        rollback["Si hay error: ROLLBACK y propagar excepción"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["Carrito: lockForUpdate y cargar cliente y lineas.producto"]
        header["Presupuesto.create: cliente_id, datos_cliente, total e instantanea"]
        lines["Presupuesto.lineas.createMany: LineaPresupuesto"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: presupuestos y lineas_presupuesto")]
    end
    form --> post
    post --> quote
    quote --> service
    service --> load
    load --> valid
    valid -->|No: RuntimeException| rollback
    rollback -->|Error de negocio capturado por el controlador| error
    valid -->|Sí| copy
    copy --> header
    header --> lines
    lines --> db
    db --> commit
    commit --> redirect
    header -->|Fallo de escritura| rollback
    lines -->|Fallo de escritura| rollback
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,error claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class post claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class quote,redirect claseC;
    classDef claseS fill:#E8F3FF,stroke:#518BC4,color:#213247;
    class service,valid,copy,commit,rollback claseS;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class load,header,lines claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

El controlador transforma `RuntimeException` en un error del carrito. Las demás excepciones siguen hacia el manejador de Laravel tras revertirse la transacción. La cabecera y todas las líneas quedan guardadas juntas, o ninguna se confirma.

## Consultar el documento sin recalcularlo

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /carrito/presupuestos/id · binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        show["CarritoController.showQuote: cargar lineas"]
        ret["Entregar el presupuesto guardado a Blade"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        quote["Presupuesto y LineaPresupuesto: datos históricos"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: cabecera y líneas del presupuesto")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        view["usuario/carrito/quote: cliente, nombre histórico, unidades, importes y total"]
    end
    get --> show
    show --> quote
    quote --> db
    db --> ret
    ret --> view
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class show,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class quote claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class view claseV;
```

Los nombres e importes no cambian al editar el catálogo. Si se borra un producto, `lineas_presupuesto.producto_id` queda a NULL y se conserva `producto_nombre`. Si se borra el cliente, `presupuestos.cliente_id` queda a NULL y permanece `datos_cliente`.
