# Actividad de clase UT2 Parte I: Administración de una tienda de informática

La empresa **NEXIUM SOFTWARE FACTORY SL** necesita organizar los contenidos de su futura tienda en Internet. En esta primera parte desarrollarás una aplicación que permita administrar productos informáticos, publicar avisos y gestionar fotografías del escaparate.

El acceso a la administración será directo, sin registro, inicio de sesión ni roles.

## 1. Panel de administración

La dirección `/` redirigirá a `/admin`. El panel dará acceso a tres módulos: Productos, Avisos y Escaparate.

Incluye navegación horizontal, una cabecera y un pie con el nombre de la empresa, definido en la configuración. Reutiliza una estructura común de Blade y adapta los formularios y listados a pantallas pequeñas.

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
| Precio | Entero entre 1 y 10.000.000 céntimos; se mostrará en euros. |
| Tipo | Portátil, periférico o componente. |
| Características | Información opcional almacenada en JSON según el tipo. |
| Fotografía | Obligatoria al crear; opcional al editar para conservar la actual. |

Representa el tipo con un enum cuyos valores sean `portatil`, `periferico` y `componente`. Los portátiles podrán tener memoria RAM, expresada como un entero entre 1 y 256 GB. Los periféricos y componentes podrán tener una conexión, con un máximo de 80 caracteres. Guarda únicamente las características correspondientes al tipo elegido.

Cada producto pertenecerá a una categoría y una categoría podrá contener varios productos. Las categorías se cargarán mediante un seeder y se elegirán en el formulario; no se pide un CRUD de categorías.

### Listado y formularios

- Permite buscar por nombre y filtrar por tipo.
- Ordena los resultados por nombre y pagina de cinco en cinco.
- Conserva la búsqueda y el tipo al cambiar de página.
- Muestra la categoría de cada artículo sin realizar una consulta adicional por producto.
- Incluye enlaces para consultar, editar y eliminar cada producto.
- Comparte el formulario de alta y edición mediante un parcial Blade.
- Valida los datos mediante un FormRequest y utiliza Route Model Binding para localizar los productos.
- Al editar, permite desmarcar la disponibilidad y conserva los campos anteriores si hay errores.

### Fotografías

Admite JPG, PNG y WebP de hasta 2 MB, comprobados en el servidor. Guarda los archivos en el disco público y únicamente su ruta en la base de datos.

Muestra la foto en el listado, el detalle y la edición. Si no se adjunta otra al editar, conserva la actual. Si se sustituye correctamente, elimina el archivo anterior. Al borrar el producto, elimina también su fotografía.

Si falla la creación o la actualización, evita dejar una fotografía nueva sin registro y conserva la anterior. Los productos iniciales podrán no tener foto; muestra un marcador adecuado en ese caso.

## 3. Tablón de avisos

Permite listar y publicar avisos. Cada aviso tendrá un título obligatorio de hasta 120 caracteres y un contenido obligatorio.

Ordena el listado del más reciente al más antiguo. Después de publicar, redirige al listado y muestra una confirmación. Los avisos deberán mantenerse al recargar la página. No se pide editar ni eliminar avisos.

## 4. Fotografías del escaparate

Crea una galería independiente de las fotografías de los productos. Desde administración se podrá consultar el listado, abrir el formulario de subida y eliminar fotografías.

Cada registro tendrá un nombre y un autor obligatorios, ambos de hasta 120 caracteres, y una imagen JPG, PNG o WebP de hasta 2 MB.

Utiliza un formulario que permita adjuntar archivos y el almacenamiento público de Laravel. Al eliminar una fotografía, retira tanto el registro como el archivo.

## 5. Base de datos y organización

Utiliza PHP, Laravel, Composer, Blade, Eloquent y MariaDB en versiones compatibles.

Las tablas de la aplicación son: categorias, productos, avisos e imagenes, además del registro de migraciones de Laravel.

- Crea migraciones reversibles y las claves foráneas necesarias.
- Define explícitamente las tablas, los atributos asignables y los casts de los modelos.
- Prepara seeders separados con al menos tres categorías, doce productos y un aviso.
- Ejecuta los seeders en el orden adecuado. Repetirlos no debe duplicar los datos iniciales.
- Separa las vistas, los controladores y la validación. Las vistas se encargarán de presentar la información.
- Protege los formularios con CSRF y utiliza los métodos HTTP de creación, actualización y borrado.
- Muestra errores contextualizados en español y mensajes de confirmación tras las operaciones correctas.

Mantén una solución sencilla: controladores directos, modelos Eloquent, un FormRequest para productos y parciales para el HTML compartido.

## 6. Comprobación y entrega

Comprueba el CRUD completo, la validación, el filtro, la paginación, la publicación de avisos y el ciclo de vida de las imágenes. Verifica también que puedes reconstruir una base de datos vacía y que no hay enlaces a módulos todavía inexistentes.

