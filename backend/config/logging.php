<?php
return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'operations'],
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],
        'operations' => [
            'driver' => 'single',
            'path' => storage_path('logs/operations.log'),
            'level' => 'info',
        ],
        'api' => [
            'driver' => 'single',
            'path' => storage_path('logs/api.log'),
            'level' => 'info',
        ],
    ],
];
