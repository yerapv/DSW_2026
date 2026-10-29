# Admin: consultar pedidos y cambiar su estado

[Índice de diagramas](../README.md) · **Área Admin**

`AdminPedidoController` no permite crear ni eliminar pedidos. El formulario de modificación acepta únicamente el estado; cliente, método, productos e importes se conservan.

| Petición | Acción |
| --- | --- |
| GET `/admin/pedidos` | `index` |
| GET `/admin/pedidos/historico` | `historico` |
| GET `/admin/pedidos/{pedido}` | `show` |
| GET `/admin/pedidos/{pedido}/edit` | `edit` |
| PUT/PATCH `/admin/pedidos/{pedido}` | `update` |

## Consultar gestión e histórico

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /admin/pedidos o /admin/pedidos/historico"]
        filter["Validar estado con Rule::enum y cliente_id existente"]
        detail["GET /admin/pedidos/id · binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        c["AdminPedidoController.index o historico: listado"]
        ret["Preparar pedidos, clientes y EstadoPedido::cases"]
        show["AdminPedidoController.show"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["Pedido: filtrar por cliente y estado; ordenar por id descendente; paginate(5) y withQueryString"]
        snap["Pedido: datos_cliente, metodo_pago_nombre e instantanea guardados"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: pedidos y clientes")]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        v["admin/pedidos/index o historico + _filtros y _tabla"]
        d["admin/pedidos/show + _lineas: datos históricos y estado actual"]
    end
    r --> filter
    filter --> c
    c --> m
    m --> db
    db --> ret
    ret --> v
    v -->|Ver detalle| detail
    detail --> show
    show --> snap
    snap --> d
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r,filter,detail claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,ret,show claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class m,snap claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class v,d claseV;
```

## Cambiar únicamente el estado

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /admin/pedidos/id/edit · binding"]
        put["PUT/PATCH /admin/pedidos/id · CSRF"]
        validate["Rule::enum de EstadoPedido"]
        ok{"¿Estado válido?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · ADMIN"]
        edit["AdminPedidoController.edit"]
        update["AdminPedidoController.update: construir solo el cambio estado"]
        redir["302 a /admin/pedidos; consultar tabla actualizada"]
    end
    subgraph V_CAPA["V · VISTA BLADE · ADMIN"]
        form["admin/pedidos/edit + _form: select EstadoPedido, @csrf y @method PUT separados"]
        err["Volver con errores y conservar el estado enviado"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        save["Pedido.update: únicamente estado; cast EstadoPedido"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: actualizar pedidos.estado")]
    end
    get --> edit
    edit --> form
    form --> put
    put --> validate
    validate --> ok
    ok -->|No| err
    err --> form
    ok -->|Sí| update
    update --> save
    save --> db
    db --> redir
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,put,validate,ok claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class edit,update,redir claseC;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,err claseV;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class save claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

`EstadoPedido` es un enum de enteros: **1** pendiente de cobro, **2** en preparación, **3** completado y **4** cancelado. Cambiar el estado no vuelve a calcular precios ni altera las instantáneas. Un registro inexistente produce 404; una petición de escritura rechazada por CSRF produce 419.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controlador:** [AdminPedidoController](../../app/Http/Controllers/AdminPedidoController.php).
- **Modelos:** [Pedido](../../app/Models/Pedido.php) · [Cliente](../../app/Models/Cliente.php) · [MetodoPago](../../app/Models/MetodoPago.php).
- **Vistas Blade:** [index](../../resources/views/admin/pedidos/index.blade.php) · [historico](../../resources/views/admin/pedidos/historico.blade.php) · [show](../../resources/views/admin/pedidos/show.blade.php) · [edit](../../resources/views/admin/pedidos/edit.blade.php) · [_filtros](../../resources/views/admin/pedidos/_filtros.blade.php) · [_tabla](../../resources/views/admin/pedidos/_tabla.blade.php) · [_lineas](../../resources/views/admin/pedidos/_lineas.blade.php) · [_form](../../resources/views/admin/pedidos/_form.blade.php).
- **Validación y soporte:** [Enum de estados](../../app/Enums/EstadoPedido.php).
- **Layouts:** [Layout general](../../resources/views/layouts/app.blade.php) · [Layout administrativo](../../resources/views/layouts/admin.blade.php).
