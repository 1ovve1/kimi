<?php

declare(strict_types=1);

return [
    'commands' => [
        'interactive' => [
            'name' => 'Интерактивный режим :status',
            'enabled' => 'Интерактивный режим включен 👀',
            'disabled' => 'Интерактивный режим выключен 🙈',
        ],
        'reset' => [
            'name' => 'Сбросить память (:count)',
            'info' => 'Было удалено :count сообщений 😢',
        ],
        'start' => [
            'greetings' => "Привет 🖐\n ",
        ],
    ],
];
