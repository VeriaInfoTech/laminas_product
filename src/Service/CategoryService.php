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

class CategoryService implements ServiceInterface
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
        AccountService $accountService,
        UtilityService $utilityService,
        MetaService    $metaService,
                       $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->metaService = $metaService;
        $this->config = $config;
    }

    public function getCategoryList(object|array $params): array
    {
        $params['limit'] = 200;
        $list = $this->metaService->getMetaValueList($params)['data']['list'];
        $tree = [];
        $parent = [];
        foreach ($list as $item) {
            if ($item['parent_id'] == 0) {
                $parent[] = $item;
            } else {
                $tree[(string)$item['parent_id']][] = $item;
            }
        }
        foreach ($parent as $key => $node) {
            $parent[$key]['children'] = $tree[(string)$node['id']];
        }
        return $parent;
    }

}
