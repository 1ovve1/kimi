<?php

return [
    'default' => env('GPT_MODEL', 'gpt-4o'),
    'key' => env('GPT_KEY', ''),

    /** @link \App\Services\OpenAI\AIModelsEnum */
    'models' => [
        'gpt-4o' => [
            'limit' => 124000,
        ],
        'gpt-4o-mini' => [
            'limit' => 16384,
        ],
        'gpt-4' => [
            'limit' => 8000,
        ],
        'gpt-3.5-turbo' => [
            'limit' => 16384,
        ],
    ],
];
