<?php

return [
    'src_path' => resource_path('views'),
    'cache_path' => env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),
    'debug' => false,
    'aliases' => [
        // 'csrf' => 'components/form/csrf',
    ],
];