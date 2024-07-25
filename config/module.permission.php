<?php

return [


    'api' => [],

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