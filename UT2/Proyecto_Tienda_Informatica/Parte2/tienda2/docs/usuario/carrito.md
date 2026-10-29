# Usuario: consultar el carrito y sumar productos repetidos

[Índice de diagramas](../README.md) · **Área Usuario**

`CarritoController.index` muestra el carrito del cliente elegido. `add` suma unidades e importes de un producto en **una sola línea**. El precio no se toma del formulario.

## Consultar el carrito

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /carrito con cliente_id opcional"]
        val["Validar cliente_id entero y existente"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["CarritoController.index"]
        has{"¿Hay cliente seleccionado?"}
        ret["Sumar precio_centimos de las líneas y preparar datos"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        m["Carrito.paraCliente: firstOrCreate por cliente_id; cargar lineas.producto"]
        quotes["Cliente.presupuestos: ordenar y paginate(5) con query"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes, carritos, lineas_carrito, productos y presupuestos")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        select["usuario/carrito/index: selector; sin cliente no crea carrito"]
        view["usuario/carrito/index: líneas, unidades, importes, total y presupuestos"]
    end
    r --> val
    val --> c
    c --> has
    has -->|No| select
    has -->|Sí| m
    m --> db
    m --> quotes
    quotes --> db
    db --> ret
    ret --> view
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r,val claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,has,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class m,quotes claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class select,view claseV;
```

## Añadir un producto o acumular más unidades

```mermaid
flowchart TD
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["Formulario en productos o carrito: cliente_id, producto_id, cantidad y @csrf"]
        err["Mostrar errores y conservar lo introducido"]
    end
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        post["POST /carrito/lineas · CSRF"]
        val["Validar cliente y producto existentes; cantidad entera de 1 a 100"]
        ok{"¿Datos válidos?"}
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        add["CarritoController.add"]
        tx["DB::transaction: bloquear fila del carrito con lockForUpdate"]
        sum["Cantidad nueva = cantidad anterior + unidades añadidas"]
        money["Importe nuevo = importe anterior + precio actual del producto por unidades añadidas"]
        redirect["COMMIT y 302 a /carrito?cliente_id=..."]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        find["Cliente y Producto: buscar; Carrito.paraCliente"]
        line["Carrito.lineas.firstOrNew por producto_id"]
        save["LineaCarrito.save: crear o actualizar la única línea"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: línea única por carrito_id y producto_id")]
    end
    form --> post
    post --> val
    val --> ok
    ok -->|No| err
    ok -->|Sí| add
    add --> find
    find --> tx
    tx --> line
    line --> sum
    sum --> money
    money --> save
    save --> db
    db --> redirect
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,err claseV;
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class post,val,ok claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class add,tx,sum,money,redirect claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class find,line,save claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
```

`lineas_carrito.precio_centimos` es el **importe completo de la línea**, no su precio unitario. Si se añaden 2 unidades a 10 € y después 3 a 12 €, queda una línea de 5 unidades y 56 €. Consultar el carrito no recalcula el importe con el catálogo. Con un precio sin cambios de 10 €, 2 + 3 unidades suman 50 €.

La migración agrupa los duplicados anteriores sumando cantidades e importes y establece una restricción única. La transacción de alta usa el mismo bloqueo del carrito que el pago; si falla una escritura, no se confirma la suma. [Generar presupuesto](presupuestos.md) · [Comprar](compra.md).
