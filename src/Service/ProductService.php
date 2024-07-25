<?php

namespace Product\Service;

use Content\Service\ItemService;
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

    /** @var ItemService */
    protected ItemService $itemService;

    public function __construct(
        AccountService      $accountService,
        UtilityService      $utilityService,
                            ItemService $itemService,
                            $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->config = $config;
    }

    public function getItemList(object|array $requestBody)
    {
    }

}
