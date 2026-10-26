# Actividad de clase UT2 Parte II: Administración de una tienda de informática

## 1. Continuación del proyecto

La empresa **NEXIUM SOFTWARE FACTORY SL** ya dispone de la administración desarrollada en `tienda1`. Ahora quiere abrir la tienda a sus visitantes y demostrar un proceso de compra.

Operaciones que se deben añadir al panel de administración:

Se deben añadir un CRUD de gestión de clientes para la realización de las compras. (No se iniciará sesión, ni se usarán sesiones, ni cookies).

La gestión de pedidos permitirá consultar los pedidos del cliente y cambiar únicamente su estado: en preparación, completado, pendiente de cobro o cancelado. No se podrán crear, eliminar ni modificar los demás datos de los pedidos desde administración.

Se deben añadir un CRUD de gestión de métodos de pago (tarjeta de credito, contrareembolso, etc.).

Se podrán visualizar los datos del histórico de pedidos en una sección del panel de gestión.

Crea **`tienda2`** como una copia independiente de la primera entrega. Conserva la administración de productos, avisos y fotografías con sus CRUD completos, los listados en tablas, los botones Editar y Eliminar de cada fila y el botón Volver de todas las pantallas. **Este enunciado describe únicamente las funcionalidades y adaptaciones que se añaden a `tienda1`**; no es necesario rehacer los módulos ya resueltos.

Conserva también la introducción de precios en euros, como 24,50 o 25,65, y su conversión interna a céntimos enteros.

Mantén la estructura MVC y el código sencillo. La aplicación seguirá sin inicio de sesión: los clientes serán ficticios, cada cliente tendrá su propio carrito y los pagos serán simulados. El histórico de pedidos seguirá siendo consultable sin iniciar sesión.

## 2. Nueva navegación pública

Añade un área de usuario con sus propias vistas y un layout que comparta la estructura general de la aplicación. La página `/` pasará a ser la bienvenida de la tienda.

- Incorpora navegación Horizontal para explorar la tienda.
- Sitúa **Usuario**, **Admin** y **Carrito** en la parte derecha de la cabecera.

- **Usuario** abrirá `/usuario`, con un menú **TU ESPACIO** para acceder a formularios, servicios, carrito e histórico. Cada cliente tendrá asociado un espacio denominado "Tu Espacio" descrito más adelante. (No usaremos inicio sesión, sesiones o cookies).

- **Admin** conservará el acceso a `/admin` y su navegación de gestión.

- **Carrito** abrirá `/carrito`.
- La página principal del área de Usuario no mostrará el botón **Volver**. El resto de las páginas, incluidos los resultados de formularios y los documentos históricos, mantendrán un botón **Volver** con un destino coherente.

## 3. Presentación y catálogo para visitantes

### Nuevas páginas de presentación

- Inicio: bienvenida y presentación de la empresa.
- Novedades: permite filtrar por portátil, periférico o componente. Conserva la selección y contempla que no haya resultados.

- Añade un catálogo público que lea los productos existentes. Permitirá buscar por nombre, filtrar por tipo, ordenar por nombre, paginar de cinco en cinco conservando los parámetros y abrir el detalle de un artículo. Muestra sus datos, categoría, fotografía y características, y permite añadir unidades al carrito desde el listado y el detalle.

- Añade también páginas para consultar las categorías con sus productos, los avisos del más reciente al más antiguo y la galería del escaparate con nombre y autor.

- Las fotografías utilizarán URLs que se resuelvan desde el dominio y puerto de la página actual. Los productos de muestra sin fotografía mantendrán su marcador hasta que se les asigne una desde administración.

Estas vistas de consulta no tendrán controles para crear, editar ni borrar el catálogo o los contenidos. La gestión continuará en `/admin`.

### Ficha de cliente

Utiliza un FormRequest para validar nombre obligatorio de hasta 80 caracteres, tipo de cliente entre particular, empresa y estudiante, edad entera entre 0 y 120 y correo obligatorio válido de hasta 150 caracteres.

Guarda los datos validados en la tabla clientes, incluyendo tipo, edad y dirección opcional. Si se ha seleccionado un cliente, carga su ficha y actualiza ese registro; si no, crea un cliente nuevo. Tras guardar, redirige a una página que consulte y muestre la ficha desde MySQL, con acceso a su edición y al carrito. Los clientes anteriores podrán mantener tipo y edad sin indicar.

## 4. Nuevos servicios

### Tarifas de envío

Implementa una clase que reciba el peso y la urgencia y calcule:

**Precio en euros = 3 + (peso en kg × 0,80) + 5 si es urgente.**

Redondea a dos decimales y muestra ambos resultados para 2,5 kg: **5 € normal** y **10 € urgente**.

### Presupuesto de montaje

Solicita entre 1 y 100 equipos y permite elegir instalación y urgencia. El montaje costará **25 € por equipo**; la instalación añadirá **10 € por equipo** y la urgencia, **15 € por encargo**.

Define las tarifas en configuración y calcula el resultado en una clase de servicio utilizando céntimos enteros. Dos equipos con instalación y urgencia deberán costar **85 €**. Muestra el resultado sin guardar el encargo.

### Simulación de financiación

Solicita edad, importe, antigüedad laboral en meses y confirmación de documentación completa. Valida el formato y los valores no negativos; edad y antigüedad serán enteras.

Una clase independiente evaluará estas reglas ficticias:

| Situación | Resultado |
| --- | --- |
| Menor de 18 años | Error. |
| Importe superior a 3.000 € | Error. |
| Documentación incompleta | Error. |
| Antigüedad laboral inferior a seis meses | Advertencia. |

Muestra la validez, los errores y las advertencias. Una advertencia por sí sola no invalida la solicitud. No se guardará ni tramitará financiación real.

## 5. Carrito y presupuesto histórico

### Selección de productos

Cada cliente tendrá su propio carrito, vinculado mediante `carritos.cliente_id`. Antes de añadir productos, el visitante seleccionará un cliente en el catálogo o en el carrito. Cada envío del formulario añadirá entre 1 y 100 unidades de un producto existente al carrito de ese cliente. Cada producto ocupará una sola línea: si ya está, suma las unidades y el importe de las nuevas unidades. Conserva los importes de las unidades anteriores, aunque el precio del catálogo haya cambiado. Cambiar de cliente permitirá consultar su selección sin mezclar productos ni importes entre carritos.

Calcula el importe en el servidor multiplicando el precio unitario por la cantidad y guárdalo en la línea para conservar el precio aplicado al añadirla. Muestra todas las líneas y el total. Guarda los importes como céntimos enteros y preséntalos en euros.

### Generación de presupuestos

Permite generar un presupuesto mediante un servicio y una transacción. El presupuesto pertenecerá al cliente del carrito mediante `presupuestos.cliente_id` y conservará una copia de sus datos. Guarda el total y una instantánea JSON con el identificador y el nombre del producto, la cantidad y el importe de cada línea. Además, crea los registros de `lineas_presupuesto`, relacionados con el presupuesto y con cada producto, incluyendo nombre histórico, cantidad e importe. La cabecera y todas las líneas se guardarán juntas o se revertirán si falla una operación. El precio ya estará fijado desde que se añadió la línea; el nombre se copiará al generar el documento.

El presupuesto tendrá su propia URL, se listará entre los presupuestos de su cliente y se consultará desde sus líneas y datos guardados, sin recalcular con el catálogo actual. Cambiar el nombre o el precio de un producto no alterará documentos anteriores.

Rechaza la generación si el carrito está vacío. Presupuestar conservará el carrito y no realizará una compra.

## 6. Compra simulada e histórico de pedidos

### Confirmación de compra

Desde el carrito del cliente seleccionado, muestra un resumen con su nombre y permite elegir un método de pago activo de los gestionados en administración. El pedido pertenecerá al mismo cliente que el carrito. Incluye inicialmente **tarjeta simulada** y **transferencia simulada**, sin pedir datos bancarios ni iniciar sesión.

Al confirmar:

1. Valida que el cliente existe, que el método de pago está activo y que el carrito no está vacío.
2. Guarda un pedido con cliente, total, método, estado `preparando` (En preparación) e instantáneas de las líneas y de los datos del cliente y el nombre del método de pago.
3. Retira del carrito de ese cliente las líneas compradas, conservando los carritos de los demás clientes.
4. Redirige al detalle del pedido guardado, donde se podrá consultar su estado.

La creación del pedido y la retirada de líneas se realizarán en una única transacción. Un fallo deberá revertir la operación completa. Repetir la confirmación sobre un carrito ya vaciado no creará otro pedido.

### Histórico

Añade un listado compartido con cinco pedidos por página, del más reciente al más antiguo. Muestra número, fecha, método de pago, estado, total y enlace al detalle.

Los detalles leerán las instantáneas guardadas y se conservarán aunque se modifiquen o eliminen los productos, clientes o métodos de pago originales. Ofrece acceso al histórico desde Usuario, Carrito y una sección propia del panel administrativo. El estado mostrado será el estado actual del pedido.

## 7. Comprobación y entrega de la ampliación

Comprueba la navegación pública, los filtros, los formularios, los cálculos, el precio fijado al añadir al carrito, la conservación histórica de presupuestos y pedidos y la reversión de una compra fallida. Verifica que la administración sigue funcionando y que no se pueden borrar productos incluidos en el carrito.

Entrega **`tienda2`** con la aplicación completa, los nuevos tests y la documentación actualizada. La instalación y las pruebas utilizarán recursos propios, independientes de `tienda1`. Excluye credenciales personales, dependencias instaladas y cachés.

En la presentación, recorre la tienda, genera un presupuesto y realiza una compra simulada. Explica por qué una modificación del catálogo no cambia los documentos históricos.

## 8. Gestión de clientes, pedidos y métodos de pago

Esta ampliación administrativa corresponde únicamente a `tienda2`. Reutiliza las tablas y el botón Volver del resto de la administración. Clientes y métodos de pago mantendrán sus acciones CRUD; en pedidos se ofrecerán únicamente Ver detalle y Cambiar estado.

### Clientes para las compras

Implementa un CRUD con nombre obligatorio de hasta 80 caracteres, correo obligatorio válido de hasta 150 caracteres y dirección opcional de hasta 255 caracteres. Seleccionar un cliente al comprar no iniciará sesión ni creará una cuenta de usuario.

### Métodos de pago

Implementa un CRUD con nombre obligatorio de hasta 80 caracteres, código único obligatorio de hasta 20 caracteres y disponibilidad mediante un checkbox. El código admitirá minúsculas, números, guiones y guiones bajos. Los métodos nuevos que estén activos aparecerán en el formulario de pago; los inactivos no se podrán utilizar para nuevas compras públicas. Todos los pagos seguirán siendo simulados.

### Pedidos y estados

Permite listar y consultar los pedidos generados al comprar desde el carrito. Desde administración únicamente se podrá cambiar su estado: **En preparación**, **Completado**, **Pendiente de cobro** o **Cancelado**.

Define los cuatro estados en el enum PHP `EstadoPedido`, con valores enteros para MySQL (1 pendiente de cobro, 2 en preparación, 3 completado y 4 cancelado) y nombres para mostrarlos. Aplica el cast del enum en Pedido y valida los formularios y filtros con `Rule::enum`. El formulario solo incluirá el estado. No se podrán crear ni eliminar pedidos, ni modificar el cliente, el método de pago, los productos, las cantidades o los importes. El servidor actualizará únicamente el estado, aunque se envíen otros campos manipulados. Tras el pago simulado, el pedido se guardará en preparación.

### Histórico en el panel

Añade un acceso específico al histórico administrativo. Mostrará fecha, cliente, método, estado y total, con enlace al detalle, filtros por cliente y estado y paginación de cinco pedidos. Las instantáneas permitirán consultar los datos guardados aunque después se borre el cliente o el método de pago. Los pedidos se conservarán en el histórico; no habrá una acción para borrarlos.

### Persistencia en MySQL

Utiliza la base MySQL `tienda2`, independiente de `tienda1`. Añade migraciones para ampliar los clientes, crear `metodos_pago` y relacionar los pedidos con clientes y métodos, conservando los pedidos que ya existan. Incluye seeders de métodos de pago sin duplicados. Las pruebas utilizarán exclusivamente la base MySQL independiente `tienda2_test`.

### Asociaciones de carritos, presupuestos y productos

Los modelos Cliente, Carrito y Presupuesto declararán sus relaciones Eloquent. Un cliente podrá tener un carrito y varios presupuestos; cada presupuesto tendrá varias líneas asociadas a sus productos. Los importes se conservarán en las líneas y nunca se recalcularán al consultar documentos anteriores.

La tabla de fotografías del escaparate de `tienda2` se llamará **`escaparate_imagenes`**. Las rutas de la galería y el nombre del modelo Imagen podrán mantenerse, declarando la tabla de forma explícita. El cambio de nombre se hará con una migración que conserve los registros y las rutas de los archivos.

Los registros anteriores sin propietario o sin identificador de producto se conservarán sin inventar asociaciones. Las nuevas operaciones exigirán un cliente y vincularán cada línea con el producto seleccionado. Si después se borra un cliente o un producto, los presupuestos conservarán los nombres, cantidades e importes históricos.

### Enumerados y paginación administrativa

`TipoProducto` será un enum de enteros: 1 portátil, 2 periférico y 3 componente. `EstadoPedido` también será un enum de enteros. Las columnas `productos.tipo` y `pedidos.estado` almacenarán números, mientras los formularios y tablas mostrarán los nombres. Una migración convertirá los datos existentes sin perder registros.

Todos los listados de administración (productos, avisos, escaparate, clientes, métodos de pago, pedidos e histórico) mostrarán cinco registros por página y enlaces con números de página. Los filtros se conservarán al cambiar de página.
