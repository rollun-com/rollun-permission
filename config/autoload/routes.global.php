<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            ],
    ],

    'routes' => [
        /*
         * if you use rollun-datastore uncomment this. and add Config.
         [
            'name' => 'api.rest',
            'path' => '/api/rest[/{resourceName}[/{id}]]',
            'middleware' => 'api-rest',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
         ],
        */
        /*
         * if you use rollun-callback uncomment this. and add Config.
         [
            'name' => 'interrupt.callback',
            'path' => '/interrupt/callback',
            'middleware' => 'interrupt-callback',
            'allowed_methods' => ['POST'],
         ],
         */
        [
            'name' => 'webhook',
            'path' => '/webhook[/{resourceName}]',
            'middleware' => 'webhook',
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'login',
            'path' => '/login',
            'middleware' => 'authPipe',
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'logout',
            'path' => '/logout',
            'middleware' => \rollun\permission\Auth\Middleware\LogoutAction::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'base-test-page',
            'path' => '/base/test-page/[{name}]',
            'middleware' => 'base-service',
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'user',
            'path' => '/user',
            'middleware' => 'user-service',
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'home-page',
            'path' => '/[{name}]',
            'middleware' => 'home-service',
            'allowed_methods' => ['GET'],
        ],

    ],
];
