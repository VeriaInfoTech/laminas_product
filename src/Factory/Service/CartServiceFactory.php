<?php

namespace Product\Factory\Service;

use Content\Service\ItemService;
use Content\Service\MetaService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Product\Service\CartService;
use Product\Service\ProductService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use User\Service\AccountService;
use User\Service\UtilityService;

class CartServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return CartService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CartService
    {
         return new CartService(
            $container->get(AccountService::class),
            $container->get(UtilityService::class),
            $container->get(ItemService::class),
            $container->get(MetaService::class),
            $container->get(ProductService::class),
        );
    }
}
