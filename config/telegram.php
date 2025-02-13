<?php

return [
    'limitations' => [
        'messages' => [
            'total_length' => 4096,
            'throttle' => 30,
        ],
    ],
    'admin' => env('TELEGRAM_ADMIN_ID', 0),
];
