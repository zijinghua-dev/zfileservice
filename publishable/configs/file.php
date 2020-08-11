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
    ],
    'lock_interval' => 60,
    'file_interval' => 1,
    'file_cycle_num' => 20,
    'redis_aging_time' => 60*60,
    'file_lock' => '_file_lock'
];