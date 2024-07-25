<?php

namespace Product\Factory\Service;

use Content\Service\ItemService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Product\Service\CategoryService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Product\Service\ProductService;
use User\Service\AccountService;
use User\Service\UtilityService;

class CategoryServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return CategoryService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CategoryService
    {
        $config = $container->get('config');

        return new CategoryService(
            $container->get(AccountService::class),
            $container->get(UtilityService::class),
            $container->get(ItemService::class),
            ///TODO: kerloper: set config array in global if need it
            []
        );
    }
}
