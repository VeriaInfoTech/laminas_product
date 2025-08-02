<?php

namespace Product\Service;

use Content\Service\ItemService;
use Content\Service\MetaService;
use User\Service\AccountService;
use User\Service\UtilityService;

class CommentService implements ServiceInterface
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
        ItemService $itemService,
        ProductService $productService,
    ) {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->productService = $productService;
    }

    ///comment slug includes => comment-item_id-time()
    public function addComment(object|array|null $requestBody, mixed $account): array
    {

        $time = time();
        $productId = $requestBody['product_id'] ?? 0;
        $product = $this->productService->getItem(['type' => 'id', 'id' => $productId]);
        if (empty($product)) {
            return [];
        }
        $params = [
            'user_id'     => $account['id'],
            'type'        => 'comment',
            'status'      => 0,
            'parent_id'   => $productId,
            'slug'        => 'comment-' . $productId . '-' . $time,
            'time_create' => $time
        ];
        $requestBody['user_id'] = $account['id'];
        $requestBody['user'] = $account;
        $requestBody['item'] = $product;
        $requestBody['item_id'] = $productId;
        $information = array_merge($params, $requestBody);
        $params['information'] = json_encode($information);
        $this->itemService->addItem($params, $account);
        unset($requestBody['item']);
        return $requestBody;
    }

    public function commentList($request)
    {
        $commentList = [];
        try {
            foreach ($this->itemService->getItemList($request)['data']['list'] ?? [] as $comment) {
                $commentList[] = $comment;
            };
        } catch (\Exception $e) {
            $commentList = [];
        }
        return $commentList;
    }

    private function canonizeComment(array $commentData, string $type = 'comment'): array
    {
        if (empty($commentData)) {
            return [];
        }
        return $commentData['information'];
    }

    public function updateComment(array $params, mixed $account): array
    {
        $this->clearComment($account);
        return $this->addComment($params, $account);
    }

    public function clearComment(mixed $account): void
    {
        $this->itemService->destroyItem(['slug' => 'comment-' . $account['id']]);
    }

}
