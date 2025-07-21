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
        AccountService $accountService,
        UtilityService $utilityService,
        MetaService    $metaService,
        ItemService    $itemService,
                       $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->metaService = $metaService;
        $this->itemService = $itemService;
        $this->config = $config;
    }

    public function getItemList(object|array $params): array
    {
        $params['type'] = 'product';
        if(isset($params['order'])){
            $params['order'] = str_replace('price','title',$params['order']);
            $params['order'] = str_replace('view','slug',$params['order']);
            $params['order'] = str_replace('sale','time_update',$params['order']);
        }
        $data = $this->itemService->getItemList($params);
        $list = $data['data']['list'];
        $listType1 = [];
        $categoryList = $this->getCategoryObjectList();
        $brandList = $this->getBrandObjectList();
        foreach ($list as $item) {
            $listType1[] = $this->canonizeProductType1([
                "item" => $item,
                "category_list" => $categoryList,
                "brand_list" => $brandList
            ]);
        }
        $data['data']['list'] = $listType1;
        $data['data']['filter'] = $params;
        return $data;

    }

    ///TODO: move this business to add entity
    private function getCategoryObjectList(): array
    {
        $list = $this->metaService->getMetaValueList(['key' => 'category'])['data']['list'];
        $objectList = [];
        foreach ($list as $item) {
            $objectList[$item['slug']] = $item;
        }
        return $objectList;
    }

    ///TODO: move this business to add entity
    private function getBrandObjectList(): array
    {
        $list = $this->metaService->getMetaValueList(['key' => 'brand'])['data']['list'];
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
        $item = $data['item'] ?? [];
        if (empty($item))
            return [];

        $brandList = $data['brand_list'] ?? [];
        $isSpecial = $this->hasMetaKey($data['item'], 'product-special');
        $img ="https://karen.kerloper.com/uploads/compose/{$item['slug']}.png?reload=".time();
        $product = [
            'id' => $item['id'] ?? null,
            'abstract'=> $item['abstract'] ?? null,
            'slug' => $item['slug'] ?? $img,
//            'img' => $img,
            'img' => $item['image']['src'] ?? null,
            'trending' => $this->hasMetaKey($data['item'], 'product-trend'),
            'topRated' => (bool)rand(0, 1),
            'bestSeller' => (bool)rand(0, 1),
            'new' => !$isSpecial,
            'special_sale' => $isSpecial,
            'banner' => true,
            'banner_img' => $img,
            'sale_of_per' => 10,
            'related_images' =>$item['related_images']??[
                $img,
                $img,
                $img,
                $img,
            ],
            'thumb_img' => $img,
            'big_img' => $img,
            'parentCategory' => 'Electronics',
            'category' => '',
            'brand' => '',
            'title' => $item['title'] ?? '',
            'price' => 120000,
            'old_price' => 249.99,
            'rating' => rand(0, 5),
            'quantity' => 50,
            'orderQuantity' => 0,
//            'sm_desc' => 'لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
            'sm_desc' => $item['abstract'] ??'لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
            'sizes' => [],
            'colors' => [],
            'weight' => [],
            'dimension' => null,
            'reviews' => [
            ],
            'details' => [
//                'details_text' => 'لورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
                'details_text' => $item['description'] ??'dddddلورم ایپسوم (Lorem Ipsum) متنی است آزمایشی و بی‌معنی در صنعت چاپ و طراحی گرافیک. این متن به‌طور کامل از متن‌های کلاسیک و قدیمی لاتین گرفته شده است. از آنجا که این متن بی‌معنی است، می‌توان آن را به‌عنوان یک پاراگراف موقت در طراحی و چاپ استفاده کرد تا مشتریان نهایی نظری در مورد طراحی گرافیک یا صفحه‌آرایی داشته باشند',
//                'details_list' => ['dsafsdf'],
//                'details_text_2' => ['aaaa']
            ],
        ];

        $product['extra'] = [];
        $product['meta'] =$item['meta'];
        $product['extra']['category'] = $this->filterObjects(['list' => $item['meta'] ?? [], 'value' => 'category']);
        if ($product['extra']['category']) {
            $product['category'] = implode(
                ",",
                array_map(function ($item) {
                    return $item["meta_information"]["title"];
                },
                    $product['extra']['category']
                )
            );
        }

        $productBrandList = $this->filterObjects(['list' => $item['meta'] ?? [], 'value' => 'brand']);
        $product['extra']['brand'] = (sizeof($productBrandList) > 0) ? $productBrandList[0] : [];
        if ($product['extra']['brand']) {
            $product['brand'] = isset($product['extra']['brand']['meta_value']) ? $brandList[$product['extra']['brand']['meta_value']]['title'] ?? '' : '';
        }

        $productPriceList = $this->filterObjects(['list' => $item['meta'] ?? [], 'value' => 'price']);
        $product['extra']['price'] = (sizeof($productPriceList) > 0) ? $productPriceList[0] : [];
        if ($product['extra']['price']) {
            $product['price'] = (int)$product['extra']['price']['meta_value'] ?? 150000;
        }
        $product['middle_banner'] = "https://karen.kerloper.com/uploads/product.jpg";
        return $product;
    }

    public function getItem(object|array $requestBody): array
    {
        $requestBody['type'] = $requestBody['type'] ?? '';

        $categoryList = $this->getCategoryObjectList();
        $brandList = $this->getBrandObjectList();

        $product = $this->canonizeProductType1([
            "item" => $this->itemService->getItem($requestBody[$requestBody['type']], $requestBody['type']),
            "category_list" => $categoryList,
            "brand_list" => $brandList
        ]);

        if (!empty($product)) {

            $filter = [
                'limit' => 8,
                'page' => 1,
            ];
            if (!empty($product['extra']['category'])) {
                $filter['category_list'] = array_column($product['extra']['category'], 'meta_value');
            }
            if (!empty($product['extra']['brand'])) {
                $filter['brand_list'] = [$product['extra']['brand']['meta_value']];
            }
            $product['related_products'] = $this->getItemList($filter)['data']['list'];

            $product['comments'] = [
                [
                    "user" => "سامان شاخص",
                    "data_time" => "2025-07-21 18:45",
                    "text" => "این محصول واقعا عالیه، خیلی راضیم."
                ],
                [
                    "user" => "نرگس احمدی",
                    "data_time" => "2025-07-20 10:12",
                    "text" => "ارسال سریع بود و بسته‌بندی مناسب داشت."
                ],
                [
                    "user" => "محسن رضایی",
                    "data_time" => "2025-07-19 21:30",
                    "text" => "کیفیتش خوبه ولی قیمتش یه مقدار بالاست."
                ],
                [
                    "user" => "الهه کریمی",
                    "data_time" => "2025-07-18 14:03",
                    "text" => "پیشنهاد می‌کنم قبل از خرید اندازه‌اش رو چک کنید."
                ],
                [
                    "user" => "علی عباسی",
                    "data_time" => "2025-07-17 09:57",
                    "text" => "من برای بار دوم این محصول رو خریدم، عالیه."
                ]
            ];

        }


        return $product;
    }

    private function hasMetaKey($data, $key): bool
    {
        if (isset($data['meta']) && is_array($data['meta'])) {
            foreach ($data['meta'] as $meta) {
                if (isset($meta['meta_key']) && $meta['meta_key'] === $key) {
                    return true;
                }
            }
        }
        return false;
    }



    public function getNumberFromId($id): int
    {
        // Define the pool of numbers
        $numbers = [4, 5, 6];
        $count = count($numbers);

        // Use the ID to select a number and ensure no consecutive duplicates
        $currentIndex = $id % $count;
        $previousIndex = ($id - 1) % $count;

        // If the current index matches the previous index, shift by 1
        if ($currentIndex === $previousIndex) {
            $currentIndex = ($currentIndex + 1) % $count;
        }

        return $numbers[$currentIndex];
    }

}
