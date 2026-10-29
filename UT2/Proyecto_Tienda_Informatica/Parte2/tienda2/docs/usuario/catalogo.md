# Usuario: consultar el catálogo y los productos

[Índice de diagramas](../README.md) · **Área Usuario**

El catálogo público está en **`/productos`**. Usa `TiendaController`, mientras que `/admin/productos` usa `ProductoController`. El visitante puede consultar y añadir unidades al carrito; la edición del catálogo corresponde a Admin.

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /productos?q=...&tipo=...&cliente_id=..."]
        val["Validar TipoProducto y cliente_id opcionales"]
        detail["GET /productos/id: Route Model Binding o 404"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["TiendaController.index"]
        ret["Preparar productos, clientes y cliente seleccionado"]
        show["TiendaController.show: cargar categoría"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        query["Producto: buscar nombre, filtrar tipo y cargar categoria; paginate(5) y withQueryString"]
        client["Cliente: cargar selector y selección"]
        product["Producto: buscar por id y convertir tipo mediante el cast"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: productos, categorias y clientes")]
        file["Storage public: resolver URL de fotografía o marcador"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        list["usuario/productos/index: filtro, tarjetas y formulario Añadir con @csrf"]
        one["usuario/productos/show: características y formulario de compra"]
        cart["POST /carrito/lineas: continuar en el diagrama de carrito"]
    end
    r --> val
    val --> c
    c --> query
    c --> client
    query --> db
    client --> db
    db --> ret
    ret --> list
    list -->|Ver ficha| detail
    detail --> product
    product --> show
    show --> one
    file -.-> list
    file -.-> one
    list -->|Cliente, producto y unidades| cart
    one -->|Cliente, producto y unidades| cart
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r,val,detail claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,ret,show claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class query,client,product claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class list,one,cart claseV;
```

El formulario envía un producto existente y entre 1 y 100 unidades por envío. El precio se obtiene en el servidor. [Agrupar y valorar el carrito](carrito.md).

`TipoProducto` se guarda como entero: 1 portátil, 2 periférico y 3 componente; las vistas usan `nombre()` para mostrar el texto. Las URL de fotos se resuelven en el dominio y puerto actuales.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controladores:** [TiendaController](../../app/Http/Controllers/TiendaController.php).
- **Modelos:** [Producto](../../app/Models/Producto.php) · [Categoria](../../app/Models/Categoria.php) · [Cliente](../../app/Models/Cliente.php).
- **Vistas Blade:** [usuario/productos/index](../../resources/views/usuario/productos/index.blade.php) · [usuario/productos/show](../../resources/views/usuario/productos/show.blade.php).
- **Servicios, reglas y configuración:** [Tipos de producto](../../app/Enums/TipoProducto.php) · [Configuración del disco de imágenes](../../config/filesystems.php).
- **Layout público:** [Layout de Usuario](../../resources/views/layouts/usuario.blade.php) · [Layout general](../../resources/views/layouts/app.blade.php).
