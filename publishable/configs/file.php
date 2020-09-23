<?php
return [
    'project' => env('APP_NAME'),
    'driver' => env('FILESYSTEM_DRIVER'),
    'file_sevice' => [
        'host' => env('FILE_SERVICE_HOST'),
        'api' => '/api/file'
    ],
    'lock_interval' => 60,
    'file_interval' => 1,
    'file_cycle_num' => 20,
    'redis_aging_time' => 60*60,
    'file_lock' => '_file_lock',
    'filename_length' => 32,
    'image_max_side_length' => 3000,   //图片最大宽度
];
