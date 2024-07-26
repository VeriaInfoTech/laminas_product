<?php

namespace Product\Service;

use Content\Service\MetaService;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function explode;
use function in_array;
use function is_object;
use function json_decode;

class BrandService implements ServiceInterface
{

    /** @var AccountService */
    protected AccountService $accountService;


    /** @var UtilityService */
    protected UtilityService $utilityService;
    /* @var array */
    protected array $config;

    /** @var MetaService */
    protected MetaService $metaService;

    public function __construct(
        AccountService      $accountService,
        UtilityService      $utilityService,
                            MetaService $metaService,
                            $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->metaService = $metaService;
        $this->config = $config;
    }

    public function getBrandList(object|array $params = []): array
    {
        return  $this->metaService->getMetaValueList($params);
    }

}
