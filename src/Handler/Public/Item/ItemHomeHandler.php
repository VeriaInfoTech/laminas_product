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


        $result = [];
        if( isset($requestBody['caller'])) {
            switch ($requestBody['caller']) {
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
                            "list" => $this->itemService->getItemList(['type' => 'blog', 'limit' => 3, 'page' => 1])['data']['list'],

                        ],
                        'category_list'=>$this->categoryService->getCategoryList(['key'=>'category']),
                        'brand_list'=>$this->brandService->getBrandList(['key'=>'brand'])['data']['list']
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
}