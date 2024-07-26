<?php

namespace Product\Factory\Repository;

use Product\Model\Item;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Product\Repository\ProductRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


class ProductRepositoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return ProductRepository
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ProductRepository
    {
        return new ProductRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Item(0,0,0,0,0,0,0,0,0,"",0)
        );
    }
}
