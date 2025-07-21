<?php

namespace Product\Handler\Public\Item;

use Content\Service\ItemService;
use Product\Service\BrandService;
use Product\Service\CategoryService;
use Product\Service\ProductService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ItemHomeHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var ProductService */
    protected ProductService $productService;

    /** @var ItemService */
    protected ItemService $itemService;


    /** @var CategoryService */
    protected CategoryService $categoryService;

    /** @var BrandService */
    protected BrandService $brandService;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        ItemService              $itemService,
        ProductService           $productService,
        CategoryService          $categoryService,
        BrandService             $brandService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->itemService = $itemService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get request body
        $requestBody = $request->getParsedBody();

        if (!isset($requestBody['caller'])) {
            $requestBody['caller'] = 'base-shop';
        }

        $result = [];
        if (isset($requestBody['caller'])) {
            switch ($requestBody['caller']) {
                case 'shop':
                    $result = [
                        "banner" => [
                            // "image"=>"https://karen.kerloper.com/uploads/1920-1000.jpg?reload=".time(),
                            "image" => "https://karen.kerloper.com/uploads/0003.jpg",
                            "subhead" => " تجربه تغییر رکوردها با ",
                            "title" => "مکمل های ورزشی پیشرو در بدنسازی"
                        ],
                        "slider_product" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_popular' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محصولات محبوب",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                        ]
                    ];
                    break;
                case 'base-karen-shop':


                    $sliders = $this->itemService->getItem('home-slider-2024', 'slug');
                    $result = [

                        "sliders" => isset($sliders['banner_list']) ? $sliders['banner_list'] : [],
                        "sub_slogan" => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک",
                        "middle_sliders" => [
                            [
                                "id" => "294",
                                "image" => "https://karen.kerloper.com/uploads/e5.jpg?reload=" . time(),
                                "title" => "کیدز بار ",
                                "subtitle" => "توت فرنگی و وانیل",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "id" => "294",
                                "image" => "https://karen.kerloper.com/uploads/e6.jpg?reload=" . time(),
                                "title" => "کیدز بار ",
                                "subtitle" => "توت فرنگی و وانیل",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "id" => "288",
                                "image" => "https://karen.kerloper.com/uploads/e7.jpg?reload=" . time(),
                                "title" => "پروتئین بار ",
                                "subtitle" => "شکلاتی",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "image" => "https://karen.kerloper.com/uploads/e1.jpg?reload=" . time(),
                                "title" => "انرژی بار ",
                                "id" => "289",
                                "subtitle" => "طعم شکلاتی - کافیین دار",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "id" => "289",
                                "image" => "https://karen.kerloper.com/uploads/e2.jpg?reload=" . time(),
                                "title" => "انرژی بار ",
                                "subtitle" => "طعم شکلاتی - کافیین دار",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "id" => "289",
                                "image" => "https://karen.kerloper.com/uploads/e3.jpg?reload=" . time(),
                                "title" => "انرژی بار ",
                                "subtitle" => "طعم شکلاتی - کافیین دار",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],
                            [
                                "id" => "289",
                                "image" => "https://karen.kerloper.com/uploads/e4.jpg?reload=" . time(),
                                "title" => "انرژی بار ",
                                "subtitle" => "طعم شکلاتی - کافیین دار",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ],

                        ],
                        "middle_banner" => [
                            "image" => "https://karen.kerloper.com/uploads/creatineinim.jpg",
                            "title" => "Creatine",
                            "fa_text" => "افزایش قدرت عضلانی استقامت و کارایی بدن",
                            "text" => "Increase muscle , strength Power & performance",
                        ],
                        "bottom_banner" => [
                            [
                                "image" => "https://karen.kerloper.com/uploads/11.jpg?reload=" . time(),
                                "title" => " ",
                                "subtitle" => " ",
                                "en_title" => "  "
                            ],
                            [
                                "image" => "https://karen.kerloper.com/uploads/22.jpg?reload=" . time(),
                                "title" => "افزایش وزن",
                                "subtitle" => "کربو مسیو",
                                "en_title" => " "
                            ],
                            [
                                "image" => "https://karen.kerloper.com/uploads/33.jpg?reload=" . time(),
                                "title" => " آمینو اسید ها",
                                "subtitle" => "آمینو وی",
                                "en_title" => " "
                            ],
                            [
                                "image" => "https://karen.kerloper.com/uploads/44.jpg?reload=" . time(),
                                "title" => " ",
                                "subtitle" => " ",
                                "en_title" => "  "
                            ],
                            [
                                "image" => "https://karen.kerloper.com/uploads/slider02.png",
                                "title" => "افزایش وزن",
                                "subtitle" => "مکس بی سی دبل ای مکس ماسل",
                                "en_title" => "MAXMUSCLE MAX BCCA"
                            ]
                        ],
                        "trend_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                // 'product_trend' => 1,
                                'limit' => 8,
                                'page' => 2
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محصولات ترند",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "latest_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                // 'product_trend' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "آخرین محصولات",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "popular_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_popular' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محبوب ترین ها",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "middle_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_middle_section' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محصولات ویژه",
                            "button_link" => "/products/?specialProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "special_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_special' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "فروش ویژه",
                            "button_link" => "/products/?specialProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "https://api.topinbiz.com/upload/ver-03/right-side-main.png",
                            "abstract" => ""
                        ],
                        "blog_list" => [

                            "title" => "وبلاگ",
                            "more_link" => "/blog/",
                            "more_title" => "مشاهده بیشتر",
                            "list" => [
                                [
                                    "id" => 103,
                                    "slug" => "blog-tset-1",
                                    "type" => "blog",
                                    "uri" => "",
                                    "url" => "",
                                    "title" => "وبلاگ تست دو",
                                    "sub_title" => "",
                                    "thumbnail" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "image" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "page_title" => "",
                                    "meta" => [],
                                    "meta_html" => [],
                                    "body" => [],
                                    "time_create" => 1700608256,
                                    "status" => 0,
                                    "user_id" => 23,
                                    "abstract" => "به متنی آزمایشی و بی معنی در صنعت چاپ، صفحه آرایی و طراحی گرافیک گفته می شود. طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارایه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد. معمولا طراحان گرافیک برای صفحه آرایی، نخست از متن های آزمایشی و بی معنی استفاده می کنند تا صرفا به مشتری یا صاحب کار خود نشان دهند که صفحه طراحی یا صفحه بندی شده بعد از اینکه متن در آن قرار گیرد چگونه به نظر می رسد و قلم ها و اندازه بندی ها چگونه در نظر گرفته شده است. از آنجایی که طراحان عموما نویسنده متن نیستند و وظیفه رعایت حق تکثیر متون را ندارند و در همان حال کار آنها به نوعی وابسته به متن می باشد آنها با استفاده از محتویات ساختگی، صفحه گرافیکی خود را صفحه آرایی می کنند تا مرحله طراحی و صفحه بندی را به پایان برند."
                                ],

                                [
                                    "id" => 103,
                                    "slug" => "blog-tset-1",
                                    "type" => "blog",
                                    "uri" => "",
                                    "url" => "",
                                    "title" => "وبلاگ تست دو",
                                    "sub_title" => "",
                                    "thumbnail" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "image" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "page_title" => "",
                                    "meta" => [],
                                    "meta_html" => [],
                                    "body" => [],
                                    "time_create" => 1700608256,
                                    "status" => 0,
                                    "user_id" => 23,
                                    "abstract" => "به متنی آزمایشی و بی معنی در صنعت چاپ، صفحه آرایی و طراحی گرافیک گفته می شود. طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارایه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد. معمولا طراحان گرافیک برای صفحه آرایی، نخست از متن های آزمایشی و بی معنی استفاده می کنند تا صرفا به مشتری یا صاحب کار خود نشان دهند که صفحه طراحی یا صفحه بندی شده بعد از اینکه متن در آن قرار گیرد چگونه به نظر می رسد و قلم ها و اندازه بندی ها چگونه در نظر گرفته شده است. از آنجایی که طراحان عموما نویسنده متن نیستند و وظیفه رعایت حق تکثیر متون را ندارند و در همان حال کار آنها به نوعی وابسته به متن می باشد آنها با استفاده از محتویات ساختگی، صفحه گرافیکی خود را صفحه آرایی می کنند تا مرحله طراحی و صفحه بندی را به پایان برند."
                                ],

                                [
                                    "id" => 103,
                                    "slug" => "blog-tset-1",
                                    "type" => "blog",
                                    "uri" => "",
                                    "url" => "",
                                    "title" => "وبلاگ تست دو",
                                    "sub_title" => "",
                                    "thumbnail" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "image" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "page_title" => "",
                                    "meta" => [],
                                    "meta_html" => [],
                                    "body" => [],
                                    "time_create" => 1700608256,
                                    "status" => 0,
                                    "user_id" => 23,
                                    "abstract" => "به متنی آزمایشی و بی معنی در صنعت چاپ، صفحه آرایی و طراحی گرافیک گفته می شود. طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارایه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد. معمولا طراحان گرافیک برای صفحه آرایی، نخست از متن های آزمایشی و بی معنی استفاده می کنند تا صرفا به مشتری یا صاحب کار خود نشان دهند که صفحه طراحی یا صفحه بندی شده بعد از اینکه متن در آن قرار گیرد چگونه به نظر می رسد و قلم ها و اندازه بندی ها چگونه در نظر گرفته شده است. از آنجایی که طراحان عموما نویسنده متن نیستند و وظیفه رعایت حق تکثیر متون را ندارند و در همان حال کار آنها به نوعی وابسته به متن می باشد آنها با استفاده از محتویات ساختگی، صفحه گرافیکی خود را صفحه آرایی می کنند تا مرحله طراحی و صفحه بندی را به پایان برند."
                                ],

                                [
                                    "id" => 103,
                                    "slug" => "blog-tset-1",
                                    "type" => "blog",
                                    "uri" => "",
                                    "url" => "",
                                    "title" => "وبلاگ تست دو",
                                    "sub_title" => "",
                                    "thumbnail" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "image" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "page_title" => "",
                                    "meta" => [],
                                    "meta_html" => [],
                                    "body" => [],
                                    "time_create" => 1700608256,
                                    "status" => 0,
                                    "user_id" => 23,
                                    "abstract" => "به متنی آزمایشی و بی معنی در صنعت چاپ، صفحه آرایی و طراحی گرافیک گفته می شود. طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارایه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد. معمولا طراحان گرافیک برای صفحه آرایی، نخست از متن های آزمایشی و بی معنی استفاده می کنند تا صرفا به مشتری یا صاحب کار خود نشان دهند که صفحه طراحی یا صفحه بندی شده بعد از اینکه متن در آن قرار گیرد چگونه به نظر می رسد و قلم ها و اندازه بندی ها چگونه در نظر گرفته شده است. از آنجایی که طراحان عموما نویسنده متن نیستند و وظیفه رعایت حق تکثیر متون را ندارند و در همان حال کار آنها به نوعی وابسته به متن می باشد آنها با استفاده از محتویات ساختگی، صفحه گرافیکی خود را صفحه آرایی می کنند تا مرحله طراحی و صفحه بندی را به پایان برند."
                                ],

                                [
                                    "id" => 103,
                                    "slug" => "blog-tset-1",
                                    "type" => "blog",
                                    "uri" => "",
                                    "url" => "",
                                    "title" => "وبلاگ تست دو",
                                    "sub_title" => "",
                                    "thumbnail" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "image" => [
                                        "src" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                                        "alt" => "",
                                        "width" => "",
                                        "height" => ""
                                    ],
                                    "page_title" => "",
                                    "meta" => [],
                                    "meta_html" => [],
                                    "body" => [],
                                    "time_create" => 1700608256,
                                    "status" => 0,
                                    "user_id" => 23,
                                    "abstract" => "به متنی آزمایشی و بی معنی در صنعت چاپ، صفحه آرایی و طراحی گرافیک گفته می شود. طراح گرافیک از این متن به عنوان عنصری از ترکیب بندی برای پر کردن صفحه و ارایه اولیه شکل ظاهری و کلی طرح سفارش گرفته شده استفاده می نماید، تا از نظر گرافیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن باشد. معمولا طراحان گرافیک برای صفحه آرایی، نخست از متن های آزمایشی و بی معنی استفاده می کنند تا صرفا به مشتری یا صاحب کار خود نشان دهند که صفحه طراحی یا صفحه بندی شده بعد از اینکه متن در آن قرار گیرد چگونه به نظر می رسد و قلم ها و اندازه بندی ها چگونه در نظر گرفته شده است. از آنجایی که طراحان عموما نویسنده متن نیستند و وظیفه رعایت حق تکثیر متون را ندارند و در همان حال کار آنها به نوعی وابسته به متن می باشد آنها با استفاده از محتویات ساختگی، صفحه گرافیکی خود را صفحه آرایی می کنند تا مرحله طراحی و صفحه بندی را به پایان برند."
                                ],

                            ],

                        ],
                        'category_list' => $this->categoryService->getCategoryList(['key' => 'category']),

                        'category_slider' => $this->categoryService->getCategoryList(['key' => 'category'])[0]['children'],
                        'brand_list' => $this->brandService->getBrandList(['key' => 'brand'])['data']['list'],

                        "last_banner" => [
                            // "image"=>"https://karen.kerloper.com/uploads/003.jpg",
                            "image" => "https://karen.kerloper.com/uploads/b2.jpg",
                            "title" => "لورم ایپسوم",
                            "text" => "لورم لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم",
                            "subhead" => "Iso whey ",
                            "title" => "Pure protein pure powder",
                            "subtitle" => "پروتئین  وی ایزوله ",

                        ],
                        "end_section" => [
                            "image" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                            "title" => "لورم ایپسوم",
                            "subhead" => "لورم ایپسوم",
                            "text" => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم اس تلورم لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم"
                        ],
                        "brands" => [
                            "image" => "https://karen.kerloper.com/uploads/brands.png",
                        ],
                    ];
                    break;

                case 'base-shop':
                    $sliders = $this->itemService->getItem('home-slider-2024', 'slug');
                    $result = [

                        "sliders" => isset($sliders['banner_list']) ? $sliders['banner_list'] : [],
                        "trend_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_trend' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محصولات ترند",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "middle_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_middle_section' => 1,
                                'limit' => 2,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محصولات ویژه",
                            "button_link" => "/products/?specialProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "special_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_special' => 1,
                                'limit' => 4,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "فروش ویژه",
                            "button_link" => "/products/?specialProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "https://api.topinbiz.com/upload/ver-03/right-side-main.png",
                            "abstract" => ""
                        ],
                        "blog_list" => [

                            "title" => "وبلاگ",
                            "more_link" => "/blog/",
                            "more_title" => "مشاهده بیشتر",
                            "list" => $this->canonizeBlogList($this->itemService->getItemList(['type' => 'blog', 'limit' => 3, 'page' => 1])['data']['list']),

                        ],
                        'category_list' => $this->categoryService->getCategoryList(['key' => 'category']),
                        'brand_list' => $this->brandService->getBrandList(['key' => 'brand'])['data']['list'],


                        "sub_slogan" => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک",
                        "middle_banner" => [
                            "image" => "https://karen.kerloper.com/uploads/creatineinim.jpg",
                            "title" => "Creatine",
                            "fa_text" => "افزایش قدرت عضلانی استقامت و کارایی بدن",
                            "text" => "Increase muscle , strength Power & performance",
                        ],
                        "latest_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                // 'product_trend' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "آخرین محصولات",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                        "popular_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_popular' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محبوب ترین ها",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],

                        'category_slider' => $this->categoryService->getCategoryList(['key' => 'category'])[0]['children'],

                        "last_banner" => [
                            // "image"=>"https://karen.kerloper.com/uploads/003.jpg",
                            "image" => "https://karen.kerloper.com/uploads/b2.jpg",
                            "title" => "لورم ایپسوم",
                            "text" => "لورم لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم",
                            "subhead" => "Iso whey ",
                            "title" => "Pure protein pure powder",
                            "subtitle" => "پروتئین  وی ایزوله ",

                        ],
                        "end_section" => [
                            "image" => "https://karen.kerloper.com/uploads/3.jpg?reload=" . time(),
                            "title" => "لورم ایپسوم",
                            "subhead" => "لورم ایپسوم",
                            "text" => "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم اس تلورم لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم"
                        ],
                        "brands_slider" => [
                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ],

                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ],
                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ],

                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ]
                        ],

                        "brands_banner" => [
                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ],

                            [
                                "slug" => "brand_one",
                                "image" => "https://karen.kerloper.com/uploads/brands.png",
                            ]
                        ],
                        "sells_of_section" => [
                            "list" => $this->productService->getItemList([
                                'type' => 'product',
                                'product_popular' => 1,
                                'limit' => 8,
                                'page' => 1
                            ])['data']['list'],
                            "type" => "product",
                            "title" => "محبوب ترین ها",
                            "button_link" => "/products/?trendProducts=true",
                            "more_title" => "مشاهده بیشتر",
                            "background" => "",
                            "abstract" => ""
                        ],
                    ];
                    break;

            }
        }
        // Set result
        $result = [
            'result' => true,
            'data' => $result,
            'error' => [],
        ];

        return new JsonResponse($result);
    }

    private function canonizeBlogList(mixed $list)
    {
        if ($list) {
            foreach ($list as $key => $item) {
                $list[$key]['author'] = 'مدیر محتوا';
                $list[$key]['description'] = $item['abstract'];
                $list[$key]['abstract'] = "لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند";
            }
        }
        return $list;
    }
}