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
            Repository\ProductRepositoryInterface::class => Repository\ProductRepository::class,
            ],
        'factories' => [
            //start services factories
            Service\ProductService::class => Factory\Service\ProductServiceFactory::class,
            Service\BrandService::class => Factory\Service\BrandServiceFactory::class,
            Service\CategoryService::class => Factory\Service\CategoryServiceFactory::class,
            //start handlers factories
            //item handlers
            Handler\Public\Item\ItemHomeHandler::class => Factory\Handler\Public\Item\ItemHomeHandlerFactory::class,
            Handler\Public\Item\ItemListHandler::class => Factory\Handler\Public\Item\ItemListHandlerFactory::class,
            Handler\Public\Item\ItemGetHandler::class => Factory\Handler\Public\Item\ItemGetHandlerFactory::class,
            //category handlers
            Handler\Public\Category\CategoryListHandler::class => Factory\Handler\Public\Category\CategoryListHandlerFactory::class,
            //brand handlers
            Handler\Public\Brand\BrandListHandler::class => Factory\Handler\Public\Brand\BrandListHandlerFactory::class,
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
                            'home' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/home',
                                    'defaults' => [
                                        'module' => 'product',
                                        'section' => 'public',
                                        'package' => 'item',
                                        'handler' => 'home',
                                        'permission' => 'public-product-item-home',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            Handler\Public\Item\ItemHomeHandler::class
                                        ),
                                    ],
                                ],
                            ],
                            'get' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/get',
                                    'defaults' => [
                                        'module' => 'product',
                                        'section' => 'public',
                                        'package' => 'item',
                                        'handler' => 'get',
                                        'permission' => 'public-product-item-get',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            Handler\Public\Item\ItemGetHandler::class
                                        ),
                                    ],
                                ],
                            ],
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
                    'category' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/category',
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
                                        'package' => 'category',
                                        'handler' => 'list',
                                        'permission' => 'public-product-category-list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            Handler\Public\Category\CategoryListHandler::class
                                        ),
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'brand' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/brand',
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
                                        'package' => 'brand',
                                        'handler' => 'list',
                                        'permission' => 'public-product-brand-list',
                                        'controller' => PipeSpec::class,
                                        'middleware' => new PipeSpec(
                                            RequestPreparationMiddleware::class,
                                            SecurityMiddleware::class,
                                            Handler\Public\Brand\BrandListHandler::class
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
            'admin_product' => [
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