<?php

namespace Product\Repository;

use Laminas\Db\ResultSet\HydratingResultSet;

interface ProductRepositoryInterface
{
    public function getProduct(array $params): object|array;

    public function getProductList(array $params = []): HydratingResultSet|array;

    public function getProductCount(array $params = []): int;

    public function addProduct(array $params): object|array;

    public function editProduct(array $params): object|array;

    public function deleteProduct(array $params): void;
 
}
