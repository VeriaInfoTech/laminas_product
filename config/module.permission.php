<?php

return [


    'api' => [
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