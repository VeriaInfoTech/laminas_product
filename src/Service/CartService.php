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

    public function __construct(
        AccountService $accountService,
        UtilityService $utilityService,
        MetaService    $metaService,
        ItemService    $itemService
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->metaService = $metaService;
    }

    public function addCart(object|array|null $requestBody, mixed $account): array
    {
        $item = $this->itemService->getItem('cart','slug',['user_id'=>5]);
        return [];
    }

}
