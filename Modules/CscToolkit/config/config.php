<?php

return [
    'name'            => 'CscToolkit',
    'removebg_api_key'=> env('REMOVE_BG_API_KEY', ''),
    'max_photo_size'  => 10240, // KB
    'max_pdf_pages'   => 20,
    'storage_disk'    => 'public',

    'photo_presets' => [
        'passport_india' => ['width' => 35, 'height' => 45, 'max_kb' => 500],
        'pan_card'       => ['width' => 25, 'height' => 35, 'max_kb' => 100],
        'aadhaar'        => ['width' => 35, 'height' => 45, 'max_kb' => 100],
    ],
];