<?php
return [
    'project' => env('APP_NAME'),
    'driver' => env('FILESYSTEM_DRIVER'),
    'file_sevice' => [
        'host' => env('FILE_SERVICE_HOST'),
        'api' => [
            'upload' => '/api/v1/file/upload',
            'show' => '/api/v1/file'
        ]
    ]
];