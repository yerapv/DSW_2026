<?php

return [
    // Disco utilizado cuando una operación no indica otro explícitamente.
    'default' => env('FILESYSTEM_DISK', 'public'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            // Carpeta para archivos que no se sirven mediante public/storage.
            'root' => storage_path('app/private'),
            'throw' => true,
        ],
        'public' => [
            'driver' => 'local',
            // El controlador guarda aquí las portadas con store(..., 'public').
            'root' => storage_path('app/public'),
            // Ruta pública en el mismo dominio y puerto de la aplicación.
            'url' => '/storage',
            'visibility' => 'public',
            'throw' => true,
        ],
    ],
    // storage:link crea public/storage apuntando a storage/app/public.
    // Ese enlace permite que el navegador acceda a las imágenes guardadas.
    'links' => [public_path('storage') => storage_path('app/public')],
];
