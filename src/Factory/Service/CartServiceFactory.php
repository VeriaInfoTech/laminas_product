<?php

namespace Product\Factory\Service;

use Content\Service\MetaService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Product\Service\CartService;
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
        $config = $container->get('config');

        return new CartService(
            $container->get(AccountService::class),
            $container->get(UtilityService::class),
            $container->get(MetaService::class),
            ///TODO: kerloper: set config array in global if need it
            []
        );
    }
}
