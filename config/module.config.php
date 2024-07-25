<?php

namespace Product;
use Laminas\Mvc\Middleware\PipeSpec;
use Laminas\Router\Http\Literal;
use User\Middleware\AuthenticationMiddleware;
use User\Middleware\AuthorizationMiddleware;
use User\Middleware\RequestPreparationMiddleware;
use User\Middleware\SecurityMiddleware;

return [
    'service_manager' => [
        'aliases' => [

            ],
        'factories' => [
            //start services factories
            Service\ProductService::class => Factory\Service\ProductServiceFactory::class,
            //start handlers factories
            Handler\Public\Item\ItemListHandler::class => Factory\Handler\Public\Item\ItemListHandlerFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            // public section
            'public_product' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/product',
                    'defaults' => [],
                ],
                'child_routes' => [
                    'item' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/item',
                            'defaults' => [],
                        ],
                        'child_routes' => [
                            'list' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/list',
                                    'defaults' => [
                                        'module' => 'product',
                                        'section' => 'public',
                                        'package' => 'item',
                                        'handler' => 'list',
                                        'permission' => 'public-product-item-list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            Handler\Public\Item\ItemListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
            // Api section
            'api_product' => [
                'type' => Literal::class,
                'options' => [
                    'route' => 'api/product',
                    'defaults' => [],
                ],
                'child_routes' => [
                ],
            ],
            // admin section
            'admin_content' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin/product',
                    'defaults' => [],
                ],
                'child_routes' => [
                    // admin installer
                    'installer' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/installer',
                            'defaults' => [
                                'module' => 'product',
                                'section' => 'admin',
                                'package' => 'installer',
                                'handler' => 'installer',
                                'controller' => PipeSpec::class,
                                'middleware' => new PipeSpec(
                                    SecurityMiddleware::class,
                                    AuthenticationMiddleware::class,
                                    Handler\InstallerHandler::class
                                ),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];