<?php

namespace Product\Factory\Handler\Public\Item;

use Content\Service\ItemService;
use Product\Handler\Public\Item\ItemHomeHandler;
use Product\Service\BrandService;
use Product\Service\CategoryService;
use Product\Service\ProductService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ItemHomeHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return ItemHomeHandler
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ItemHomeHandler
    {
        return new ItemHomeHandler(
            $container->get(ResponseFactoryInterface::class),
            $container->get(StreamFactoryInterface::class),
            $container->get(ItemService::class),
            $container->get(ProductService::class),
            $container->get(CategoryService::class),
            $container->get(BrandService::class)
        );
    }
}
