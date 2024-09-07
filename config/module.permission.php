<?php

return [


    'api' => [
        [
            'module' => 'product',
            'section' => 'api',
            'package' => 'cart',
            'handler' => 'get',
            'permission' => 'api-product-cart-get',
            'role' => [
                'admin',
                'member',
            ],
        ],
        [
            'module' => 'product',
            'section' => 'api',
            'package' => 'cart',
            'handler' => 'clear',
            'permission' => 'api-product-cart-clear',
            'role' => [
                'admin',
                'member',
            ],
        ],
        [
            'module' => 'product',
            'section' => 'api',
            'package' => 'cart',
            'handler' => 'remove',
            'permission' => 'api-product-cart-remove',
            'role' => [
                'admin',
                'member',
            ],
        ],
        [
            'module' => 'product',
            'section' => 'api',
            'package' => 'cart',
            'handler' => 'update',
            'permission' => 'api-product-cart-update',
            'role' => [
                'admin',
                'member',
            ],
        ],
        [
            'module' => 'product',
            'section' => 'api',
            'package' => 'cart',
            'handler' => 'add',
            'permission' => 'api-product-cart-add',
            'role' => [
                'admin',
                'member',
            ],
        ],
    ],

    'admin' => [
        [
            'module' => 'product',
            'section' => 'admin',
            'package' => 'installer',
            'handler' => 'installer',
            'permission' => 'admin-product-installer-installer',
            'role' => [
                'admin',
                'member',
            ],
        ],

    ],

];