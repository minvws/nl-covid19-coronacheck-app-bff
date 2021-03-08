<?php

return [
    'disks' => [
        'cdnfiles' => [
            'driver' => 'local',
            'root'   => (str_starts_with(env('CDN_FILES_DIR'),'/') ? env('CDN_FILES_DIR') : realpath(dirname(__FILE__))."/../".env('CDN_FILES_DIR')),
        ],
    ],
];
