# Ejecutar la solución E16

Ejecutar los siguientes comandos, en orden, desde la raíz del proyecto Laravel que contiene el ejercicio:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

1. **`composer install`**: instala las dependencias PHP del proyecto en la carpeta `vendor`. Si existe `composer.lock`, utiliza las versiones indicadas en ese archivo.

2. **`cp .env.example .env`**: copia la configuración de ejemplo al archivo `.env`, donde se establecen los valores locales de la aplicación, como la conexión a la base de datos.

3. **`php artisan key:generate`**: genera la clave de cifrado de la aplicación y la guarda en `APP_KEY` dentro de `.env`.

4. **`php artisan migrate --seed`**: ejecuta las migraciones pendientes para crear o actualizar las tablas y después ejecuta los seeders definidos en el proyecto para cargar los datos iniciales. Antes de este paso, la conexión a la base de datos debe estar configurada en `.env`.

5. **`php artisan storage:link`**: crea el enlace `public/storage` hacia `storage/app/public`, permitiendo acceder desde el navegador a los archivos guardados en el disco público, como las imágenes.

6. **`php artisan serve`**: inicia el servidor de desarrollo de Laravel. Para probar la aplicación, abrir en el navegador la dirección que muestra la terminal, normalmente `http://127.0.0.1:8000`.
