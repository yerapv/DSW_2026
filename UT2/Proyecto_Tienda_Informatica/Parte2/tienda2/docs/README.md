# Documentación de tienda2: guía de diagramas y código

Este índice reúne **17 documentos Markdown con 40 diagramas Mermaid** sobre el funcionamiento de `tienda2`. La documentación diferencia el área de **Administración**, el área de **Usuario** y las capas **Modelo–Vista–Controlador (MVC)** compartidas.

Cada documento muestra las rutas HTTP, los flujos de la petición, las operaciones sobre los datos y las respuestas de Laravel. Al final incluye **«Archivos para seguir el recorrido»**, con enlaces relativos a los elementos del repositorio implicados en el flujo.

> **Sobre los enlaces al código:** el ZIP de entrada contenía los Markdown y el visor HTML, **no el árbol de código fuente Laravel**. Los enlaces a `app/`, `resources/`, `config/`, `routes/` y `database/` se han preparado para funcionar cuando esta carpeta `docs/` se sitúe en la raíz del repositorio `tienda2`. Los nombres se apoyan en la documentación y en la estructura convencional de Laravel, pero no se ha podido comprobar la existencia exacta de esos archivos. Las rutas marcadas como *ubicación orientativa* (algunos servicios y clases de dominio) son especialmente importantes de verificar.

## Cómo utilizar esta guía

1. Comienza por [MVC: Admin y Usuario](MVC.md) para comprender las capas, Laravel, Blade, Eloquent y los servicios.
2. Consulta el [modelo de datos](MODELO_DATOS.md) para identificar las entidades, relaciones y reglas de conservación.
3. Abre el proceso que te interese en las tablas siguientes y sigue el diagrama de arriba hacia abajo.
4. Utiliza la sección **Archivos para seguir el recorrido** del documento para pasar de la explicación al código fuente.

[**Abrir el visor HTML de los diagramas**](visor_tienda2.html) (incluido en el ZIP original).

## Arquitectura compartida

| Documento | Resumen | Diagramas |
| --- | --- | ---: |
| [MVC en tienda2](MVC.md) | Entrada HTTP, rutas, capas MVC, servicios, middleware y construcción de respuestas. | 1 |
| [Modelo de datos](MODELO_DATOS.md) | Entidades Eloquent, claves, relaciones, reglas de borrado e instantáneas históricas. | 1 |

## Administración

| Documento | Resumen | Diagramas |
| --- | --- | ---: |
| [Productos](admin/productos.md) | Listar, consultar, crear, editar y eliminar; validación, categorías y fotografía. | 4 |
| [Avisos](admin/avisos.md) | CRUD de avisos para el tablón público. | 4 |
| [Escaparate](admin/escaparate.md) | CRUD de fotografías, sustitución de archivos y limpieza de Storage. | 4 |
| [Clientes](admin/clientes.md) | CRUD de clientes y efecto de las reglas relacionales al borrar. | 4 |
| [Métodos de pago](admin/metodos-pago.md) | Gestión de métodos, códigos y disponibilidad. | 4 |
| [Pedidos](admin/pedidos.md) | Consulta e histórico; cambio exclusivamente del estado. | 2 |

## Usuario

| Documento | Resumen | Diagramas |
| --- | --- | ---: |
| [Tu Espacio](usuario/tu-espacio.md) | Selección de cliente y resumen de su actividad sin autenticación. | 1 |
| [Catálogo](usuario/catalogo.md) | Consulta pública de productos, filtros y acceso al carrito. | 1 |
| [Contenidos](usuario/contenidos.md) | Inicio, novedades, categorías, avisos y galería pública. | 2 |
| [Ficha de cliente](usuario/ficha-cliente.md) | Creación y edición persistentes de datos personales del cliente seleccionado. | 2 |
| [Carrito](usuario/carrito.md) | Consulta y acumulación de unidades con importes guardados. | 2 |
| [Presupuestos](usuario/presupuestos.md) | Generación transaccional y lectura de documentos históricos. | 2 |
| [Compra simulada](usuario/compra.md) | Confirmación, creación de pedido y retirada de líneas compradas. | 2 |
| [Histórico de pedidos](usuario/historico.md) | Consulta de pedidos y detalles con instantáneas. | 1 |
| [Servicios](usuario/servicios.md) | Cálculos de envío, montaje y financiación sin persistencia. | 3 |

**Total: 17 documentos y 40 diagramas.**

## Recorridos sugeridos

**Administrar un producto:** [productos en Admin](admin/productos.md) → [catálogo público](usuario/catalogo.md) → [carrito](usuario/carrito.md) → [compra simulada](usuario/compra.md) → [pedidos en Admin](admin/pedidos.md).

**Trabajar con clientes y presupuestos:** [clientes en Admin](admin/clientes.md) → [Tu Espacio](usuario/tu-espacio.md) → [ficha de cliente](usuario/ficha-cliente.md) → [carrito](usuario/carrito.md) → [presupuestos](usuario/presupuestos.md).

**Publicar información:** [avisos](admin/avisos.md) y [escaparate](admin/escaparate.md) → [contenidos públicos](usuario/contenidos.md).

## Leyenda de capas

| Capa | Responsabilidad |
| --- | --- |
| **C · Controlador (verde)** | Recibe la acción y coordina modelos, servicios, vistas y redirecciones. |
| **M · Modelo (naranja)** | Aplica consultas y operaciones Eloquent sobre los registros. |
| **V · Vista (violeta)** | Presenta los formularios, listados y resultados Blade. |
| **Servicios (azul)** | Encapsula operaciones de negocio, cálculos y transacciones. |
| **Laravel e infraestructura (gris)** | Rutas, middleware, CSRF, MySQL y archivos Storage. |

**Importante:** la compra es simulada, el cliente se identifica mediante `cliente_id` (no por inicio de sesión) y los presupuestos y pedidos conservan instantáneas históricas.
