<?php

namespace Product\Service;

use Content\Service\ItemService;
use Content\Service\MetaService;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function explode;
use function in_array;
use function is_object;
use function json_decode;

class ProductService implements ServiceInterface
{

    /** @var AccountService */
    protected AccountService $accountService;


    /** @var UtilityService */
    protected UtilityService $utilityService;
    /* @var array */
    protected array $config;

    /** @var MetaService */
    protected MetaService $metaService;

    /** @var ItemService */
    protected ItemService $itemService;

    public function __construct(
        AccountService      $accountService,
        UtilityService      $utilityService,
                            MetaService     $metaService,
                            ItemService     $itemService,
                            $config
    )
    {
        $this->accountService   = $accountService;
        $this->utilityService   = $utilityService;
        $this->metaService      = $metaService;
        $this->itemService      = $itemService;
        $this->config           = $config;
    }

    public function getItemList(object|array $params): array
    {
        $params['type']  = 'product';
        $data            = $this->itemService->getItemList($params);
        $list            = $data['data']['list'];
        $listType1       = [];
        $categoryList    = $this->getCategoryObjectList();
        $brandList       = $this->getBrandObjectList();
        foreach ($list as $item) {
            $listType1[] = $this->canonizeProductType1([
                "item"=>$item,
                "category_list"=>$categoryList,
                "brand_list"=>$brandList
            ]);
        }
        $data['data']['list'] = $listType1;
        return $data;

    }

    ///TODO: move this business to add entity
    private function getCategoryObjectList(): array
    {
        $list =  $this->metaService->getMetaValueList(['key'=>'category'])['data']['list'];
        $objectList = [];
        foreach ($list as $item) {
            $objectList[$item['slug']] = $item;
        }
        return $objectList;
    }

    ///TODO: move this business to add entity
    private function getBrandObjectList(): array
    {
        $list = $this->metaService->getMetaValueList(['key'=>'brand'])['data']['list'];
        $objectList = [];
        foreach ($list as $item) {
            $objectList[$item['slug']] = $item;
        }
        return $objectList;
    }

    ///TODO: move this business to add entity
    function filterObjects($params): array
    {
        $filteredObjects = [];
        foreach ($params['list'] as $object) {
            if (isset($object[$params['key'] ?? 'meta_key']) && $object[$params['key'] ?? 'meta_key'] === $params['value'] ?? '') {
                $filteredObjects[] = $object;
            }
        }
        return $filteredObjects;
    }

    ///TODO : move to utility class
    private function canonizeProductType1(mixed $data): array
    {
        $item = $data['item']??[];
        $brandList = $data['brand_list']??[];
        $categoryList = $data['category_list']??[];
        $product = [
            'id' => $item['id']??null,
            'img' => $item['image']?$item['image']['src']??null:null,
            'trending' => (bool)rand(0,1),
            'topRated' => (bool)rand(0,1),
            'bestSeller' => (bool)rand(0,1),
            'new' => (bool)rand(0,1),
            'special_sale' => (bool)rand(0,1),
            'banner' => true,
            'banner_img' =>  $item['image']?$item['image']['src']??null:null,
            'sale_of_per' => 10, // Default sale percentage
            'related_images' =>[],// ['image1.jpg', 'image2.jpg'],
            'thumb_img' =>  $item['image']?$item['image']['src']??null:null,
            'big_img' =>  $item['image']?$item['image']['src']??null:null,
            'parentCategory' => 'Electronics',
            'category' => '',
            'brand' => '',
            'title' =>  $item['title']??'',
            'price' => 120000,
            'old_price' => 249.99,
            'rating' =>rand(0,5),
            'quantity' => 50,
            'orderQuantity' => 0, // Default order quantity
            'sm_desc' => 'لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
            'sizes' => [],//['S', 'M', 'L'],
            'colors' => [],//['Red', 'Blue', 'Green'],
            'weight' =>[],// 0.5, // Default weight in kilograms
            'dimension' => null,//10x15x5 cm', // Default dimensions
            'reviews' => [
                //[
                //    'img' => 'user1.jpg',
                //    'name' => 'John Doe',
                //    'time' => '2024-07-27',
                //    'rating' => 4,
                //    'children' => true,
                //],
                // Add more review entries as needed
            ],
            'details' => [
                'details_text' => 'لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
                'details_list' => [],//['Feature 1', 'Feature 2', 'Feature 3'],
                'details_text_2' => []//'Additional details if necessary.',
            ],
        ];

        $product['extra']['category']  =  $this->filterObjects(['list'=>$item['meta']??[] ,'value'=>'category']);
        if($product['extra']['category']){
            $product['category'] = implode(
                ",",
                array_map(function ($item) {
                    return $item["meta_information"]["title"];
                },
                    $product['extra']['category']
                )
            );
        }

        $productBrandList =$this->filterObjects(['list'=>$item['meta']??[] ,'value'=>'brand']);
        $product['extra']['brand']  =  (sizeof($productBrandList)>0)?$productBrandList[0]:[];
        if($product['extra']['brand']){
            $product['brand'] = isset($product['extra']['brand']['meta_value'])?$brandList[$product['extra']['brand']['meta_value']]['title']??'':'';
        }

        $productPriceList =$this->filterObjects(['list'=>$item['meta']??[] ,'value'=>'price']);
        $product['extra']['price']  =  (sizeof($productPriceList)>0)?$productPriceList[0]:[];
        if($product['extra']['price']){
            $product['price'] =  (int)$product['extra']['price']['meta_value']??150000 ;
        }



        return $product;
    }

}
