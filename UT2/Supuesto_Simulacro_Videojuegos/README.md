# Solución · Mi colección de videojuegos

Proyecto Laravel del simulacro sencillo de la UT2. Incluye dos modelos con relación 1:N (`Genero` y `Videojuego`), un único CRUD de videojuegos, filtro por género, validación y subida de portada. Hay tres pantallas: listado, alta y edición.

El enunciado y las vistas entregables se encuentran en [Simulacro_Examen](../Simulacro_Examen/enunciado.md). Este proyecto es la **solución del profesorado**; no debe entregarse durante el examen.

## Ejecutar

Requisitos: PHP 8.3 o superior, Composer, MySQL y las extensiones PHP habituales de Laravel, incluida `pdo_mysql`. Las pruebas de imágenes también necesitan GD. Las dependencias quedan fijadas en `composer.lock`. No se necesita Node ni conexión a Internet durante el examen.

Desde esta carpeta:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crea una base MySQL llamada `simulacro_ut2` y configura en `.env` el usuario y la contraseña que tengan acceso a ella. Después:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Abre `http://127.0.0.1:8000`. El seeder carga Aventura, Deportes y Estrategia. Los videojuegos se añaden desde el formulario. Puedes utilizar la [portada de prueba](../Simulacro_Examen/material_alumnado/portada_prueba.png).

Las sesiones y la caché utilizan archivos para evitar tablas auxiliares. En las imágenes se guarda la ruta relativa del disco público; `public/storage` debe enlazar con `storage/app/public`.

## Archivos de la solución

| Archivo o carpeta | Responsabilidad |
| --- | --- |
| `database/migrations` | Dos tablas y clave foránea de videojuegos hacia géneros |
| `app/Models/Genero.php` | Relación de un género con muchos videojuegos |
| `app/Models/Videojuego.php` | Relación con un género y campos asignables |
| `app/Http/Controllers/VideojuegoController.php` | Listado, alta, edición, eliminación, validación y archivos |
| `routes/web.php` | Recurso de videojuegos sin ficha individual y redirección inicial |
| `resources/views` | Las mismas cinco vistas que se entregan al alumnado |
| `database/seeders/DatabaseSeeder.php` | Géneros iniciales; no forman parte del CRUD |

## Pruebas del profesorado

Crea otra base MySQL llamada **`simulacro_ut2_test`** y permite a tu usuario de pruebas gestionar sus tablas. `phpunit.xml` fuerza ese nombre y la conexión MySQL, para separar las pruebas de la base de desarrollo. No utilices la base de un alumno para ejecutar la suite.

Con las credenciales configuradas en `.env` o en las variables de entorno:

```bash
php artisan test
```

La suite comprueba el CRUD completo, ambas direcciones de la relación, la validación de datos y archivos, la edición sin portada nueva, la sustitución, la limpieza al eliminar y la conservación de los datos originales cuando una edición es inválida.

Verificado con MySQL 8.4: **3 pruebas y 55 comprobaciones correctas**. También se han comprobado las migraciones en ambos sentidos, la importación de `generos.sql`, las rutas, la compilación de Blade y la coincidencia de las vistas entregables con la solución.

El filtro por género se ha verificado posteriormente con una prueba específica de **24 comprobaciones**, incluyendo todos los géneros, selección conservada, género sin videojuegos e identificadores inválidos. El listado y el filtro también responden correctamente en el servidor local.
