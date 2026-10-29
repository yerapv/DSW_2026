# Usuario: histórico compartido y pedidos del cliente

[Índice de diagramas](../README.md) · **Área Usuario**

`PedidoController.index` muestra todos los pedidos si no recibe cliente_id. Si se recibe, filtra por ese cliente. La consulta pública y los detalles están disponibles sin iniciar sesión.

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /pedidos con cliente_id opcional"]
        val["Validar cliente_id cuando exista"]
        one["GET /pedidos/id · binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        index["PedidoController.index"]
        ret["Preparar pedidos y selector de clientes"]
        show["PedidoController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        query["Pedido: filtro opcional por cliente, id descendente, paginate(5) y withQueryString"]
        load["Pedido: leer instantanea, datos_cliente, método copiado y estado actual"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: pedidos y clientes")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        list["usuario/pedidos/index: histórico compartido o del cliente"]
        detail["usuario/pedidos/show: líneas, total, comprador y estado"]
    end
    get --> val
    val --> index
    index --> query
    query --> db
    db --> ret
    ret --> list
    list -->|Ver pedido| one
    one --> show
    show --> load
    load --> detail
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,val,one claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class index,ret,show claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class query,load claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class list,detail claseV;
```

La instantánea del pedido es un JSON de líneas con nombre de producto, cantidad e importe. **No existe una tabla lineas_pedido en el código actual.** Cambiar o borrar productos, clientes o métodos no reescribe el documento histórico. El nombre mostrado del estado sí refleja el último cambio hecho por Admin.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controladores:** [PedidoController](../../app/Http/Controllers/PedidoController.php).
- **Modelos:** [Pedido](../../app/Models/Pedido.php) · [Cliente](../../app/Models/Cliente.php).
- **Vistas Blade:** [usuario/pedidos/index](../../resources/views/usuario/pedidos/index.blade.php) · [usuario/pedidos/show](../../resources/views/usuario/pedidos/show.blade.php).
- **Servicios, reglas y configuración:** [Estados del pedido](../../app/Enums/EstadoPedido.php).
- **Layout público:** [Layout de Usuario](../../resources/views/layouts/usuario.blade.php) · [Layout general](../../resources/views/layouts/app.blade.php).
