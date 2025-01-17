<?php

return [
    'admin' => env('KERNEL_ADMIN', 'admin'),

    'prefix' => env('KERNEL_PREFIX', 'api'),

    'token_timeout' => env('KERNEL_TOKEN_TIMEOUT'),

    'auth_user_model' => Kernel\Models\User::class,

    'register' => [
        Kernel\Routes\UserRoute::class,
        Kernel\Routes\RoleRoute::class,
        Kernel\Routes\LoginLogRoute::class,
        Kernel\Routes\UserLogRoute::class,
        Kernel\Routes\DevtoolRoute::class
    ],

    'path' => [
//        'kernel\Routes',
    ]
];
