# Modelo de datos compartido por Admin y Usuario

[Índice de diagramas](README.md) · **Arquitectura compartida**

Este diagrama pertenece a la **M de MVC**: refleja las tablas de negocio de MySQL que utilizan los modelos Eloquent. Los controladores y las vistas aparecen en los documentos de cada área, no como tablas. Se muestran las columnas relevantes, incluidos los timestamps.

```mermaid
erDiagram
    categorias ||--o{ productos : clasifica
    clientes |o--o| carritos : dispone
    carritos ||--o{ lineas_carrito : contiene
    productos ||--o{ lineas_carrito : seleccionado
    clientes |o--o{ presupuestos : recibe
    presupuestos ||--o{ lineas_presupuesto : contiene
    productos |o--o{ lineas_presupuesto : referencia
    clientes |o--o{ pedidos : realiza
    metodos_pago |o--o{ pedidos : utilizado

    categorias {
        bigint id PK
        varchar nombre
        datetime created_at
        datetime updated_at
    }
    productos {
        bigint id PK
        bigint categoria_id FK
        varchar nombre
        varchar marca
        smallint anio
        boolean disponible
        int precio_centimos
        tinyint tipo "1 portatil, 2 periferico, 3 componente"
        json caracteristicas "nullable"
        varchar ruta_imagen "nullable"
        datetime created_at
        datetime updated_at
    }
    avisos {
        bigint id PK
        varchar titulo
        text contenido
        datetime created_at
        datetime updated_at
    }
    escaparate_imagenes {
        bigint id PK
        varchar nombre
        varchar autor
        varchar ruta_imagen "nullable"
        datetime created_at
        datetime updated_at
    }
    clientes {
        bigint id PK
        varchar nombre
        varchar email "nullable para datos anteriores"
        varchar direccion "nullable"
        varchar tipo "nullable: particular, empresa o estudiante"
        tinyint edad "nullable: 0 a 120"
        datetime created_at
        datetime updated_at
    }
    carritos {
        bigint id PK
        bigint cliente_id FK,UK "nullable para datos anteriores"
        datetime created_at
        datetime updated_at
    }
    lineas_carrito {
        bigint id PK
        bigint carrito_id FK
        bigint producto_id FK
        int cantidad
        int precio_centimos "importe total de la linea"
        datetime created_at
        datetime updated_at
    }
    presupuestos {
        bigint id PK
        bigint cliente_id FK "nullable"
        json datos_cliente "instantanea; nullable"
        int total_centimos
        json instantanea "lineas copiadas"
        datetime created_at
        datetime updated_at
    }
    lineas_presupuesto {
        bigint id PK
        bigint presupuesto_id FK
        bigint producto_id FK "nullable"
        varchar producto_nombre "nombre historico"
        int cantidad
        int precio_centimos "importe total de la linea"
        datetime created_at
        datetime updated_at
    }
    metodos_pago {
        bigint id PK
        varchar codigo UK
        varchar nombre
        boolean activo
        datetime created_at
        datetime updated_at
    }
    pedidos {
        bigint id PK
        bigint cliente_id FK "nullable"
        bigint metodo_pago_id FK "nullable"
        varchar metodo_pago "codigo copiado"
        varchar metodo_pago_nombre "nombre copiado; nullable"
        json datos_cliente "instantanea; nullable"
        int total_centimos
        tinyint estado "1 pendiente, 2 preparando, 3 completado, 4 cancelado"
        json instantanea "lineas historicas del pedido"
        datetime created_at
        datetime updated_at
    }
```

`PK` identifica la clave primaria; `FK`, una clave foránea; `UK`, una restricción única. `||` significa uno obligatorio, `|o`/`o|` cero o uno y `o{` cero o muchos. La asociación con cliente admite NULL por los datos históricos y las reglas de borrado; las nuevas operaciones de compra exigen cliente.

| Modelo Eloquent | Tabla | Uso en Admin | Uso en Usuario |
| --- | --- | --- | --- |
| Categoria | categorias | Opciones de productos. | Categorías con productos. |
| Producto | productos | CRUD. | Catálogo, carrito y documentos. |
| Aviso | avisos | CRUD. | Tablón de lectura. |
| Imagen | escaparate_imagenes | CRUD y archivos. | Galería de lectura. |
| Cliente | clientes | CRUD y consulta comercial. | Tu Espacio y ficha persistente. |
| Carrito / LineaCarrito | carritos / lineas_carrito | Acceso desde cliente. | Selección y compra. |
| Presupuesto / LineaPresupuesto | presupuestos / lineas_presupuesto | Sin CRUD propio. | Generación y consulta histórica. |
| MetodoPago | metodos_pago | CRUD. | Selección de métodos activos. |
| Pedido | pedidos | Consulta y cambio de estado. | Compra e histórico. |

## Restricciones y conservación de datos

- `carritos.cliente_id` es único: cada cliente tiene como máximo un carrito.
- La pareja **carrito_id + producto_id** de `lineas_carrito` es única: productos iguales acumulan unidades e importes en una línea.
- Borrar cliente elimina su carrito y las líneas de ese carrito por cascada; deja a NULL el cliente de pedidos y presupuestos.
- Borrar presupuesto elimina sus líneas. Borrar producto deja a NULL su referencia en las líneas de presupuesto, conservando nombre e importes históricos.
- Las líneas de carrito impiden borrar productos seleccionados; el controlador comprueba esta situación antes del DELETE.
- Borrar método de pago deja a NULL su referencia en pedidos; los nombres y códigos copiados se conservan.
- Los productos de los **pedidos** se guardan en JSON; no existe una tabla de líneas de pedido. Los **presupuestos** tienen JSON y líneas relacionales.
- Los importes son enteros en céntimos. Los códigos de `EstadoPedido` y `TipoProducto` también son enteros. `clientes.tipo` es un texto y no es `TipoProducto`.

Se omiten las tablas técnicas de Laravel (usuarios, sesiones, caché y colas), que no forman parte de estos módulos de negocio. Las imágenes son archivos: sus columnas guardan rutas, no binarios.

## Archivos para seguir el recorrido

- **Modelos de catálogo y contenido:** [Categoria](../app/Models/Categoria.php) · [Producto](../app/Models/Producto.php) · [Aviso](../app/Models/Aviso.php) · [Imagen](../app/Models/Imagen.php).
- **Modelos de cliente y carrito:** [Cliente](../app/Models/Cliente.php) · [Carrito](../app/Models/Carrito.php) · [LineaCarrito](../app/Models/LineaCarrito.php).
- **Modelos de documentos y pago:** [Presupuesto](../app/Models/Presupuesto.php) · [LineaPresupuesto](../app/Models/LineaPresupuesto.php) · [MetodoPago](../app/Models/MetodoPago.php) · [Pedido](../app/Models/Pedido.php).
- **Tipos y estados:** [Tipos de producto](../app/Enums/TipoProducto.php) · [Estados de pedido](../app/Enums/EstadoPedido.php).
- **Esquema de MySQL:** [Migraciones y claves foráneas](../database/migrations).
