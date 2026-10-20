# UT2 · Laboratorio de ejercicios Laravel

Aplicación Laravel 13 que integra los **18 ejercicios existentes en esta carpeta**. La página `/` ofrece un enlace a cada ejercicio, y todas sus pantallas permiten volver al resumen.

Los títulos y descripciones del resumen proceden de los archivos `SOLUCION_EXPLICADA.md`. Los controladores, servicios, reglas, formularios y datos proceden de cada carpeta `codigo/`. Las carpetas originales E01–E18 no se han modificado.

## Ejecutar en este equipo

Las dependencias, `.env`, clave, base de datos SQLite, migraciones, datos originales y enlace de almacenamiento ya están preparados:

```bash
cd laboratorio-laravel
php artisan serve
```

Abrir **http://127.0.0.1:8000**. También se puede usar `composer run dev`.

No hace falta ejecutar npm ni Vite: las vistas son Blade y los estilos están en `public/css/laboratorio.css`.

## Instalar desde una copia limpia

Con PHP 8.5 y Composer disponibles, ejecutar desde `laboratorio-laravel/`:

```bash
composer run setup
php artisan serve
```

`setup` instala las dependencias del archivo lock, crea `.env` si falta, genera la clave, crea el archivo SQLite si falta, ejecuta migraciones y seeders y prepara `public/storage` para las imágenes de E17. Los seeders reutilizan los registros existentes al repetirse.

## Accesos

| Ejercicio | Pantalla inicial |
| --- | --- |
| E01 · Primer MVC | `/e01/bienvenida` |
| E02 · Ficha de película | `/e02/pelicula` |
| E03 · Menú de cafetería | `/e03/menu` |
| E04 · Agenda cultural con filtro GET | `/e04/eventos` |
| E05 · Calculadora de envíos POO | `/e05/envios` |
| E06 · Inscripción a taller con Request | `/e06/inscripcion` |
| E07 · Reserva con validación | `/e07/reserva` |
| E08 · FormRequest y layout | `/e08/mascotas/nueva` |
| E09 · Notas con Eloquent | `/e09/notas` |
| E10 · CRUD de biblioteca | `/e10/libros` |
| E11 · Editoriales y libros 1:N | `/e11/editoriales` |
| E12 · Alumnado y actividades N:M | `/e12/alumnos/1` |
| E13 · Filtros y paginación | `/e13/talleres` |
| E14 · Enum y JSON de vehículos | `/e14/vehiculos` |
| E15 · Service de precios de imprenta | `/e15/imprenta` |
| E16 · Reglas de solicitud de residencia | `/e16/residencia` |
| E17 · Museo y subida de imágenes | `/e17/piezas` |
| E18 · Alquiler, snapshot y transacción | `/e18/alquiler` |

E05 conserva el ejemplo original de 2,5 kg con envío normal y urgente. E06 conserva el formulario sin validación. E12 abre el alumno creado por el seeder original. E18 conserva el carrito único del ejemplo (`id = 1`).

## Datos originales

Solo se cargan los seeders que ya existían:

- **E11:** editoriales Norte y Sur; libros Isla de papel, Luz de invierno y Mar abierto.
- **E12:** Alumno Demo; actividades Ajedrez, Teatro y Robótica.
- **E13:** monitores Ana y Luis; los 18 talleres generados por el seeder original.
- **E18:** Cámara (40 €/día), Trípode (10 €/día) y Micrófono (15 €/día).

Los demás registros persistentes se crean desde los formularios de sus ejercicios. Los ejemplos en memoria de E01–E05 conservan sus datos originales.

Para volver al estado inicial, **borrando los registros creados en esta aplicación**:

```bash
php artisan migrate:fresh --seed
```

Ese comando reinicia la base de datos; las imágenes que se hayan subido siguen en `storage/app/public/piezas`. El borrado de una pieza desde E17 elimina también su imagen, como establece el modelo original.

## Organización y adaptaciones

- `app/Ejercicios/E01/` … `E18/`: clases de cada ejercicio, con namespaces separados.
- `routes/ejercicios/e01.php` … `e18.php`: rutas originales, bajo su prefijo `/eNN` y con nombres `eNN.*`.
- `resources/views/ejercicios/e01/` … `e18/`: vistas originales adaptadas al layout de navegación. E08 mantiene además su propio layout de clínica.
- `resources/views/resumen.blade.php` y `config/ejercicios.php`: página resumen y catálogo de ejercicios.
- `database/migrations/`: migraciones originales. Las de E12 estaban dentro de `codigo/app/database/` y se han colocado en la ubicación de Laravel.
- `database/seeders/E11/`, `E12/`, `E13/`, `E18/`: seeders originales, llamados por el seeder principal.
- `tests/Unit/E15/`, `E16/` y `tests/Feature/E17/`, `E18/`: pruebas originales, adaptadas a los namespaces y rutas.

Correcciones necesarias para ejecutarlos juntos:

1. **E02:** la vista consultaba `$esLarga`, pero el controlador enviaba `$peliculaLarga`; ahora usa la variable enviada.
2. **E10/E11:** ambos tenían un modelo `Libro` y una tabla `libros` con columnas diferentes. Los modelos están separados por namespace y E11 usa `libros_editoriales`; E10 conserva `libros`.
3. **Modelos:** los nombres de tabla en español se declaran explícitamente para evitar que Eloquent infiera plurales ingleses.
4. **Enlaces, acciones y redirecciones:** incluyen el prefijo de su ejercicio. El resource de E10 conserva sus operaciones y usa nombres `e10.libros.*`.
5. **E18:** el error ya existente «El carrito está vacío» se devuelve al formulario, evitando una pantalla de error al pulsar Generar sin líneas. El Service conserva su lógica.
6. **Presentación:** layout de navegación, estilos comunes y resumen de errores de validación. No se han añadido reglas de negocio ni datos de demostración distintos de los originales.

El esqueleto conserva los archivos habituales de Laravel. Laravel Boost se incluye como dependencia de desarrollo porque lo exige el `AGENTS.md` de la plantilla; no forma parte de los ejercicios.

## Usar MariaDB

Los ejercicios originales indican MariaDB. Para permitir una prueba inmediata, esta copia utiliza SQLite por defecto. Para usar una base de datos MariaDB vacía creada para esta aplicación, editar `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ut2_ejercicios
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_clave
```

Después:

```bash
php artisan config:clear
php artisan migrate --seed
```

Las pruebas automatizadas utilizan SQLite en memoria y no modifican los registros de la aplicación. La ejecución contra MariaDB no se ha verificado en este entorno.

## Verificación

```bash
php artisan test --compact
php artisan route:list --except-vendor
```

La suite incluye las pruebas originales y pruebas de integración del resumen, pantallas, enlaces, formularios, CRUD, relaciones, filtros, Enum/JSON, imágenes, precios y presupuesto histórico. No se ha realizado una revisión visual con navegador.

Referencia de instalación del framework: [documentación oficial de Laravel 13](https://laravel.com/framework/docs/13.x/installation).
