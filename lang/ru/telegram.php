<?php

declare(strict_types=1);

return [
    'commands' => [
        'interactive' => [
            'permissions_denied' => 'Данной опцией могут пользоваться только администраторы(',
            'name' => 'Интерактивный режим :status',
            'enabled' => 'Интерактивный режим включен 👀',
            'disabled' => 'Интерактивный режим выключен 🙈',
        ],
        'reset' => [
            'permissions_denied' => 'Данной опцией могут пользоваться только администраторы(',
            'name' => 'Сбросить память (:count)',
            'info' => 'Было удалено :count сообщений 😢',
        ],
        'start' => [
            'greetings' => "Привет 🖐\n ",
        ],
    ],
];
