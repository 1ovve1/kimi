<?php

declare(strict_types=1);

return [
    'commands' => [
        'start' => [
            'greetings' => "Привет 🖐\n ",
        ],
    ],
    'keyboards' => [
        'buttons' => [
            'default' => [
                'permissions_denied' => 'Данной опцией могут пользоваться только администраторы(',
            ],
            'back_to' => [
                'name' => 'Вернуться...',
            ],
            'interactive' => [
                'name' => 'Интерактивный режим :status',
                'enabled' => 'Интерактивный режим включен 👀',
                'disabled' => 'Интерактивный режим выключен 🙈',
            ],
            'reset' => [
                'name' => 'Сбросить память (:count)',
                'info' => 'Было удалено :count сообщений 😢',
            ],
            'select_character' => [
                'name' => 'Персонаж: :Name',
                'success' => 'Персонаж успешно изменен на ":Name"',
            ],
            'character' => [
                'name' => ':Name',
            ],
        ],
    ],
];
