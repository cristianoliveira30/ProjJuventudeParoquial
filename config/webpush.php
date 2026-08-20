<?php

return [
    'database_connection' => env('WEBPUSH_DB_CONNECTION', null),
    'table_name' => env('WEBPUSH_TABLE_NAME', 'push_subscriptions'),

    'vapid' => [
        'subject'     => env('VAPID_SUBJECT', 'mailto:dev@seu-dominio.com'),
        'public_key'  => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
];
