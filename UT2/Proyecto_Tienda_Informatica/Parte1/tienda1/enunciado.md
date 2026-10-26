# Actividad de clase UT2 Parte I: Administración de una tienda de informática

La empresa **NEXIUM SOFTWARE FACTORY SL** necesita organizar los contenidos de su futura tienda en Internet. En esta primera parte desarrollarás una aplicación que permita administrar productos informáticos, publicar avisos y gestionar fotografías del escaparate.

El acceso a la administración será directo, sin registro, inicio de sesión ni roles.

## 1. Panel de administración

La dirección `/` redirigirá a `/admin`. El panel dará acceso a tres módulos: Productos, Avisos y Escaparate.

Incluye navegación horizontal, una cabecera y un pie con el nombre de la empresa, definido en la configuración. Reutiliza una estructura común de Blade y adapta los formularios y listados a pantallas pequeñas.

Incluye un botón **Volver** en todas las pantallas de administración. Los listados regresarán al panel; las altas, los detalles y las ediciones regresarán al listado de su módulo. La gestión de productos, avisos y fotografías se realizará mediante **tablas**, con botones **Editar** y **Eliminar** en cada fila de datos y un acceso para crear registros.

Toda la gestión estará bajo `/admin`. Esta parte no tendrá enlaces ni pantallas de usuario, favoritos, servicios, carrito o pedidos.

## 2. Gestión de productos

Implementa el listado, el formulario de alta, la creación, el detalle, el formulario de edición, la actualización y la eliminación de productos (CRUD) con acceso a datos, migraciones, seeders. Se deben validar los datos de los productos.

### Datos y validaciones

| Dato | Condiciones |
| --- | --- |
| Nombre | Obligatorio; máximo 150 caracteres. |
| Marca | Obligatoria; máximo 120 caracteres. |
| Año | Entero entre 2000 y el año actual. |
| Disponibilidad | Valor booleano que se pueda activar y desactivar. |
| Categoría | Obligatoria y existente en la base de datos. |
| Precio | Entre 0,01 y 100.000,00 €. Se introducirá en euros, por ejemplo 24,50 o 25,65, con un máximo de dos decimales. |
| Tipo | Portátil, periférico o componente. |
| Características | Información opcional almacenada en JSON según el tipo. |
| Fotografía | Obligatoria al crear; opcional al editar para conservar la actual. |

Representa el tipo con un enum cuyos valores sean `portatil`, `periferico` y `componente`. Los portátiles podrán tener memoria RAM, expresada como un entero entre 1 y 256 GB. Los periféricos y componentes podrán tener una conexión, con un máximo de 80 caracteres. Guarda únicamente las características correspondientes al tipo elegido.

El formulario aceptará coma o punto como separador decimal y mostrará el precio con coma al editar. Convierte el importe a céntimos enteros antes de guardarlo para mantener exactos los cálculos.

Cada producto pertenecerá a una categoría y una categoría podrá contener varios productos. Las categorías se cargarán mediante un seeder y se elegirán en el formulario; no se pide un CRUD de categorías.

### Listado y formularios

- Permite buscar por nombre y filtrar por tipo.
- Ordena los resultados por nombre y pagina de cinco en cinco.
- Conserva la búsqueda y el tipo al cambiar de página.
- Muestra la categoría de cada artículo sin realizar una consulta adicional por producto.
- Muestra los productos en una tabla con enlaces para consultar y editar, y un botón para eliminar cada producto.
- Comparte el formulario de alta y edición mediante un parcial Blade.
- Valida los datos mediante un FormRequest y utiliza Route Model Binding para localizar los productos.
- Al editar, permite desmarcar la disponibilidad y conserva los campos anteriores si hay errores.

### Fotografías

Admite JPG, PNG y WebP de hasta 2 MB, comprobados en el servidor. Guarda los archivos en el disco público y únicamente su ruta en la base de datos.

Muestra la foto en el listado, el detalle y la edición. Si no se adjunta otra al editar, conserva la actual. Si se sustituye correctamente, elimina el archivo anterior. Al borrar el producto, elimina también su fotografía.

Si falla la creación o la actualización, evita dejar una fotografía nueva sin registro y conserva la anterior. Los productos iniciales podrán no tener foto; muestra un marcador adecuado en ese caso.

## 3. Tablón de avisos

Implementa el CRUD completo de avisos: listado en tabla, alta, detalle, edición y eliminación. Cada aviso tendrá un título obligatorio de hasta 120 caracteres y un contenido obligatorio. El alta y la edición compartirán el formulario y las reglas de validación.

Ordena el listado del más reciente al más antiguo. Después de crear, actualizar o eliminar, redirige al listado. Los avisos deberán mantenerse al recargar la página.

## 4. Fotografías del escaparate

Crea una galería independiente de las fotografías de los productos. Su administración tendrá un CRUD completo: listado en tabla, subida, detalle, edición y eliminación de fotografías.

Cada registro tendrá un nombre y un autor obligatorios, ambos de hasta 120 caracteres, y una imagen JPG, PNG o WebP de hasta 2 MB.

Comparte el formulario de alta y edición. Al editar, permite cambiar el nombre y el autor sin subir otra foto; si se adjunta una nueva, sustituye la anterior y elimina el archivo antiguo solo después de guardar correctamente. Si falla el guardado, conserva la fotografía anterior y retira la nueva. Al eliminar una fotografía, retira tanto el registro como el archivo.

Utiliza el almacenamiento público de Laravel y URLs de imagen que funcionen con el dominio y el puerto desde los que se accede a la aplicación. Los productos iniciales sin fotografía mostrarán un marcador hasta que se les asigne una desde administración.

## 5. Base de datos y organización

Utiliza PHP, Laravel, Composer, Blade, Eloquent y MySQL en versiones compatibles. La aplicación utilizará la base `tienda1` y las pruebas la base independiente `tienda1_test`.

Las tablas de la aplicación son: categorias, productos, avisos e imagenes, además del registro de migraciones de Laravel.

- Crea migraciones reversibles y las claves foráneas necesarias.
- Define explícitamente las tablas, los atributos asignables y los casts de los modelos.
- Prepara seeders separados con al menos tres categorías, doce productos y un aviso.
- Ejecuta los seeders en el orden adecuado. Repetirlos no debe duplicar los datos iniciales.
- Separa las vistas, los controladores y la validación. Las vistas se encargarán de presentar la información.
- Protege los formularios con CSRF y utiliza los métodos HTTP de creación, actualización y borrado.
- Muestra errores contextualizados en español y redirige al listado correspondiente tras las operaciones correctas.

Mantén una solución sencilla: controladores directos, modelos Eloquent, un FormRequest para productos y parciales para el HTML compartido.

## 6. Comprobación y entrega

Comprueba los tres CRUD completos, los botones de cada fila, los destinos de Volver, la validación, el filtro, la paginación y el ciclo de vida de las imágenes. Verifica también que puedes reconstruir una base de datos vacía y que no hay enlaces a módulos todavía inexistentes.

