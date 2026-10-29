# Usuario: confirmar la compra simulada

[Índice de diagramas](../README.md) · **Área Usuario**

`PagoController` prepara la confirmación y delega la compra en `ServicioPedidos`. Solo se procesa el carrito del cliente seleccionado. No se solicitan datos bancarios.

## Mostrar la confirmación

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /carrito/pago?cliente_id=...; validar cliente"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        create["PagoController.create"]
        empty{"¿Carrito vacío?"}
        err["302 al carrito con error"]
        ret["Calcular total y preparar confirmación"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        cart["Carrito.paraCliente: cargar cliente y lineas.producto"]
        methods["MetodoPago: solo activos, ordenados por nombre"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: carrito del cliente y métodos de pago")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/pago/create: resumen, cliente fijo, método activo y @csrf"]
    end
    get --> create
    create --> cart
    cart --> db
    db --> empty
    empty -->|Sí| err
    empty -->|No| methods
    methods --> ret
    ret --> form
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class create,empty,err,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class cart,methods claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form claseV;
```

## Guardar el pedido y retirar las líneas compradas

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["Confirmar pago: cliente_id, metodo_pago y @csrf"]
        error["Volver al carrito con error de negocio"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        post["POST /carrito/pagar · CSRF; cliente existente y método activo"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        store["PagoController.store: obtener carrito y llamar al servicio"]
        redirect["302 a /pedidos/id con cliente_id"]
    end
    subgraph S_CAPA["SERVICIOS Y DOMINIO · APOYO A MVC"]
        service["ServicioPedidos.pagar: comprobar método activo"]
        tx["Iniciar DB::transaction; bloquear carrito"]
        valid{"¿Hay cliente y líneas?"}
        copy["Copiar productos, cantidades, importes, datos del cliente y nombre del método"]
        commit["COMMIT y devolver el pedido"]
        rollback["Error: ROLLBACK; no confirmar pedido ni vaciar carrito"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        load["Carrito y lineas.producto: recuperar solo la selección del cliente"]
        save["Pedido.create: total, instantáneas y EstadoPedido::EnPreparacion"]
        delete["Carrito.lineas: eliminar solo los ids incluidos en la compra"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: insertar pedido y retirar líneas en la misma transacción")]
    end
    form --> post
    post --> store
    store --> service
    service --> tx
    tx --> load
    load --> valid
    valid -->|No| rollback
    valid -->|Sí| copy
    copy --> save
    save --> delete
    delete --> db
    db --> commit
    commit --> redirect
    save -->|Fallo| rollback
    delete -->|Fallo| rollback
    rollback -->|RuntimeException capturada por el controlador| error
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,error claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class post claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class store,redirect claseC;
    classDef claseS fill:#E8F3FF,stroke:#518BC4,color:#213247;
    class service,tx,valid,copy,commit,rollback claseS;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class load,save,delete claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

El estado inicial se guarda como **2 · En preparación**. El total se calcula con los importes aplicados de las líneas, aunque el catálogo haya cambiado. Los carritos de otros clientes permanecen intactos. Una segunda confirmación encuentra el carrito vacío y no crea otro pedido.

Si el método dejó de estar activo, el servicio lo rechaza antes de iniciar la transacción. [Consulta del pedido y el histórico](historico.md) · [Cambio de estado desde Admin](../admin/pedidos.md).

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controladores:** [PagoController](../../app/Http/Controllers/PagoController.php).
- **Modelos:** [Carrito](../../app/Models/Carrito.php) · [LineaCarrito](../../app/Models/LineaCarrito.php) · [Cliente](../../app/Models/Cliente.php) · [MetodoPago](../../app/Models/MetodoPago.php) · [Pedido](../../app/Models/Pedido.php).
- **Vistas Blade:** [usuario/pago/create](../../resources/views/usuario/pago/create.blade.php) · [usuario/pedidos/show](../../resources/views/usuario/pedidos/show.blade.php).
- **Servicios, reglas y configuración:** [Servicio de compra (ubicación orientativa)](../../app/Services/ServicioPedidos.php) · [Estados del pedido](../../app/Enums/EstadoPedido.php).
- **Layout público:** [Layout de Usuario](../../resources/views/layouts/usuario.blade.php) · [Layout general](../../resources/views/layouts/app.blade.php).
