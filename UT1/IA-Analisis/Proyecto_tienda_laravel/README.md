# Iniciar el Proyecto Laravel

# Bajamos dependencias de composer
composer install

# Creamos el fichero de configuración .env
cp .env.example .env

# y Rellenamos datos de configuración .env

# Generamos una nueva app.key
php artisan key:generate

# Creamos la base de datos y generamos datos de ejemplo en la aplicación
php artisan migrate --seed

# Arrancamos el proyecto
php artisan serve
