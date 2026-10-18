# Ejecutar la solución E05

Partir de un proyecto Laravel 13 limpio ya creado en la UT1.

1. Copiar sobre el proyecto los ficheros de `codigo/` respetando sus rutas.
2. Si hay migraciones, configurar MariaDB y ejecutar `php artisan migrate:fresh --seed` cuando exista seeder; si no, `php artisan migrate:fresh`.
3. Ejecutar `php artisan serve`.
4. Abrir la ruta indicada en el enunciado.

Ficheros incluidos:
- `routes/web.php`
- `app/Dominio/Envios/CalculadoraEnvio.php`
- `app/Http/Controllers/EnvioController.php`
- `resources/views/envios.blade.php`
