<?php

namespace Product\Handler\Public;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use User\Service\InstallerService;

class InitHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var InstallerService */
    protected InstallerService $installerService;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        InstallerService         $installerService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->installerService = $installerService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {


        $logo = '';
        $fav = '';
        $siteTitle = '';
        $toptitle= 'خرید اول ارسال رایگان کد:LAVORA2025';
        $menu = [
            [
                'level' => 'parent',
                'type'=>'link',
                'title'=>'پیشنهاد ویژه',
                'has_child' => false,
                'data' => [
                    'type' => 'product',
                    'special_suggest' => 1,
                ],
                'child' => null
            ],
            [
                'level' => 'parent',
                'type'=>'label',
                'title'=>'برندها',
                'has_child' => true,
                'value' => null,
                'child' =>[
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'ایروکس',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-irox'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'سی فور',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-see-for'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'پین رست',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-pain-rest'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'ساین اسکالپ',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-synscalp'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'ساین اسکین',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-synskin'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'دکتر ژیلا',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-dr-jila'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'پارتیس',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-partis'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'ژیوانا',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-jiwana'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'آوند',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-avand'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'بس ۲',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-bath2'
                            ]
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'بس',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'brand_list' => [
                                'meta-brand-bath'
                            ]
                        ],
                        'child' => null
                    ]
                ],
            ],
            [
                'level' => 'parent',
                'type'=>'label',
                'title'=>'آرایشی',
                'has_child' => true,
                'value' => [
                    'type' => 'product',
                    'category_list	' => [
                        'meta-category-cosmetic'
                    ]
                ],
                'child' => [
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'کرم پودر',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-foundation'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'رژ لب',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-lipstick'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'ریمل',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-mascara'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'سایه چشم',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-eyeshadow'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'رژگونه',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-blush'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'پودر',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-powder'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'کانسیلر',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-concealer'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'قلم مو',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-brush'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'پف آرایشی',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-beauty-blender'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'آینه',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-mirror'],
                        ],
                        'child' => null
                    ],
                    [
                        'level' => 'child',
                        'type' => 'link',
                        'title' => 'کیف آرایشی',
                        'has_child' => false,
                        'value' => [
                            'type' => 'product',
                            'category_list' => ['meta-category-cosmetic-bag'],
                        ],
                        'child' => null
                    ]
                ]
            ],

            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'مراقبت پوست',
                'has_child' => false,
                'value' => [
                    'type' => 'product',
                    'category_list' => ['meta-category-skin-care'],
                ],
                'child' => null
            ],
            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'بهداشت و مراقبت مو',
                'has_child' => false,
                'value' => [
                    'type' => 'product',
                    'category_list' => ['meta-category-hair-care'],
                ],
                'child' => null
            ],
            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'بهداشت دهان و دندان',
                'has_child' => false,
                'value' => [
                    'type' => 'product',
                    'category_list' => ['meta-category-oral-care'],
                ],
                'child' => null
            ],
            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'بهداشت خانه',
                'has_child' => false,
                'value' => [
                    'type' => 'product',
                    'category_list' => ['meta-category-home-care'],
                ],
                'child' => null
            ],
            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'کودک',
                'has_child' => false,
                'value' => [
                    'type' => 'product',
                    'category_list' => ['meta-category-baby-care'],
                ],
                'child' => null
            ],
            [
                'level' => 'child',
                'type' => 'link',
                'title' => 'مجله لاوورا',
                'has_child' => false,
                'value' => [
                    'type' => 'page',
                    'slug' => ['magazine'],
                ],
                'child' => null
            ]
        ];



        // Set result
        return new JsonResponse(
            [
                'result' => true,
                'data' => [
                    'logo'=>'',
                    'fav'=>'',
                    'site_title'=>'',
                    'top_title'=>$toptitle,
                    'top_banner'=>'',
                    'menu'=>$menu,
                ],
                'error' => [],
            ],
        );
    }
}
