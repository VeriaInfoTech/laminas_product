<?php

namespace Product\Repository;

use Product\Model\Item;
use Product\Model\Product;
use Product\Repository\ProductRepositoryInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Hydrator\HydratorInterface;
use RuntimeException;
use function sprintf;


class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Product Table name
     *
     * @var string
     */
    private string $tableProduct = 'product_item';

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $db;

    /**
     * @var Item
     */
    private Item $itemPrototype;

    /**
     * @var HydratorInterface
     */
    private HydratorInterface $hydrator;


    public function __construct(
        AdapterInterface  $db,
        HydratorInterface $hydrator,
        Item              $itemPrototype,
    )
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->itemPrototype = $itemPrototype;
    }

    /**
     * @param array $params
     *
     * @return HydratingResultSet|array
     */
    public function getProductList(array $params = []): HydratingResultSet|array
    {
        $where = $this->createConditional($params);
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableProduct)->where($where)->order($params['order'])->offset($params['offset'])->limit($params['limit']);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }
        $resultSet = new HydratingResultSet($this->hydrator, $this->itemPrototype);
        $resultSet->initialize($result);
        return $resultSet;
    }

    /**
     * @param array $params
     *
     * @return int
     */
    public function getProductCount(array $params = []): int
    {
        // Set where
        $columns = ['count' => new Expression('count(*)')];
        $where = $this->createConditional($params);
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableProduct)->columns($columns)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return (int)$row['count'];
    }

    /**
     * @param array $params
     *
     * @return array|object
     */
    public function addProduct(array $params): object|array
    {
        $insert = new Insert($this->tableProduct);
        $insert->values($params);
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }
        $id = $result->getGeneratedValue();
        return $this->getProduct($id);
    }

    /**
     * @param $params
     * @return object|array
     */
    public function getProduct($params): object|array
    {
        $where = [];
        $sql = new Sql($this->db);
        $select = $sql->select($this->tableProduct)->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(
                sprintf(
                    'Failed retrieving blog post with identifier "%s"; unknown database error.',
                    'parameter'
                )
            );
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->itemPrototype);
        $resultSet->initialize($result);
        $item = $resultSet->current();
        if (!$item) {
            return [];
        }
        return $item;
    }


    /**
     * @param array $params
     *
     * @return array|object
     */
    public function editProduct(array $params): object|array
    {
        $update = new Update($this->tableProduct);
        $update->set($params);
        if (isset($params["id"]))
            $update->where(['id' => $params["id"]]);
        if (isset($params["slug"]))
            $update->where(['slug' => $params["slug"]]);
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during update operation'
            );
        }
        return (isset($params["id"])) ? $this->getProduct($params["id"]) : $this->getProduct($params["slug"], "slug");
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function deleteProduct(array $params): void
    {
        $update = new Update($this->tableProduct);
        $update->set($params);
        $update->where(['id' => $params["id"]]);
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    /**
     * @param $where
     * @return void
     */
    public function destroyProduct($where): void
    {
        $update = new Delete($this->tableProduct);
        $update->where($where);
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    private function createConditional(array $params): array
    {
        return [];
    }
}
