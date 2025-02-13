<?php

declare(strict_types=1);

return [
    'keyboards' => [
        'descriptions' => [
            'start' => 'Привет 🖐\n',
            'select_character' => 'Выберите персонажа:',
        ],
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
            'rss' => [
                'name' => 'Рассылка новостей :status',
                'enabled' => 'Рассылка новостей включена 👀',
                'disabled' => 'Рассылка новостей выключена 🙈',
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
