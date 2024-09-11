<?php

namespace Product\Service;

use Content\Service\ItemService;
use Content\Service\MetaService;
use User\Service\AccountService;
use User\Service\UtilityService;

class CartService implements ServiceInterface
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


    /** @var ProductService */
    protected ProductService $productService;

    public function __construct(
        AccountService $accountService,
        UtilityService $utilityService,
        MetaService    $metaService,
        ItemService    $itemService,
        ProductService $productService
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->metaService = $metaService;
        $this->productService = $productService;
    }

    public function addCart(object|array|null $requestBody, mixed $account): array
    {
        ///TODO: handle when call this method but a cart has been stored
        $cart = $this->getCart($account);
        if (!empty($cart)) {
            $this->clearCart($account);
        }
        $params = [
            'user_id' => $account['id'],
            'type' => 'cart',
            'status' => 1,
            'slug' => 'cart-' . $account['id'],
            'time_create' => time()
        ];
        $information = array_merge($params, $requestBody);
        $params['information'] = json_encode($information);
        $this->itemService->addItem($params, $account);
        return $this->getCart($account);
    }

    public function getCart($account): array
    {
        $cart = $this->itemService->getItem('cart-' . $account['id'], 'slug', ['user_id' => $account['id']]);
        $cart = $this->canonizeCart($cart);
        return $cart;
    }

    private function canonizeCart(array $cartData, string $type = 'product'): array
    {
        if (empty($cartData)) {
            return [];
        }
        $cartItems = $cartData['cart'];
        $idList = array_column($cartItems, 'id');
        //$products = $this->itemService->getItemList(['type' => 'product', 'id' => $idList])['data']['list'];
        $products = $this->productService->getItemList(['id' => $idList])['data']['list'];
        $items = [];
        foreach ($cartItems as $cartItem) {
            foreach ($products as $product) {
                if ($product['id'] == $cartItem['id']) {
                    $inventory = (int)$this->findMetaByKey('inventory', $product)['meta_value'];
                    $price = (int)$this->findMetaByKey('price', $product)['meta_value'];
                    $items[] = [
                        'id' => $cartItem['id'],
                        'count' => $cartItem['count'],
                        'price_unit' => $price,
                        'price' => $price * $inventory,
                        'status' => $cartItem['count'] > $inventory ? 'out_of_stock' : 'in_stock',
                        'information' => $product
                    ];

                }
            }
        }
        unset($cartData['cart']);
        $cartData['cart'] = [
            'total_count' => count($items),
            'total_item_count' => array_sum(array_column($items, 'count')),
            'available_count' => count(array_filter($items, fn($item) => $item['status'] === 'in_stock')),
            'available_item_count' => array_sum(array_column(array_filter($items, fn($item) => $item['status'] === 'in_stock'), 'count')),
            'total_price' => array_sum(array_column($items, 'price')),
            'payable_price' => array_sum(array_column(array_filter($items, fn($item) => $item['status'] === 'in_stock'), 'price')),
            'items' => $items,
        ];
        return $cartData;
    }


    ///TODO: move to utils
    private function findMetaByKey(string $key, array $object): ?array
    {
        if (!isset($object['meta']) || !is_array($object['meta'])) {
            return null;
        }
        $meta = array_filter($object['meta'], function (array $meta) use ($key) {
            return isset($meta['meta_key']) && $meta['meta_key'] === $key;
        });
        return $meta ? reset($meta) : null;
    }

    public function updateCart(array $params, mixed $account): array
    {
        $this->clearCart($account);
        return $this->addCart($params, $account);
    }

    public function clearCart(mixed $account): void
    {
        $this->itemService->destroyItem(['slug' => 'cart-' . $account['id']]);
    }

}
