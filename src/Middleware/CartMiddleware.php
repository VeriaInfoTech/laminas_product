<?php

namespace Product\Middleware;

use Content\Service\ItemService;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use User\Handler\ErrorHandler;
use function implode;

class CartMiddleware implements MiddlewareInterface
{
    public array $InventoryResult = [
        'status' => true,
        'code' => StatusCodeInterface::STATUS_OK,
        'message' => '',
    ];

    protected ResponseFactoryInterface $responseFactory;
    protected StreamFactoryInterface $streamFactory;
    protected ItemService $itemService;
    protected ErrorHandler $errorHandler;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        ItemService              $itemService,
        ErrorHandler             $errorHandler
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->itemService = $itemService;
        $this->errorHandler = $errorHandler;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $routeMatch = $request->getAttribute('Laminas\Router\RouteMatch');
        $routeParams = $routeMatch->getParams();

        switch ($routeParams['validator']) {
            case 'clear':
            case 'get':
                break;

            case 'add':
                $request = $this->inventoryValid($parsedBody, $request);
                break;

            case 'remove':
                $request = $this->removeValid($parsedBody, $request);
                break;

            case 'update':
                $request = $this->updateValid($parsedBody, $request);
                break;

            case 'physical_order':
                $request = $this->physicalOrderValid($parsedBody, $request);
                break;

            case 'reserve':
                $this->reserveIsValid($parsedBody);
                break;

            default:
                $request = $request->withAttribute('status', StatusCodeInterface::STATUS_FORBIDDEN);
                $request = $request->withAttribute(
                    'error',
                    [
                        'message' => 'Inventory not set!',
                        'code' => StatusCodeInterface::STATUS_FORBIDDEN,
                    ]
                );
                return $this->errorHandler->handle($request);
        }

        if (!$this->InventoryResult['status']) {
            $request = $request->withAttribute('status', $this->InventoryResult['code']);
            $request = $request->withAttribute(
                'error',
                [
                    'message' => $this->InventoryResult['message'],
                    'code' => $this->InventoryResult['code'],
                ]
            );
            return $this->errorHandler->handle($request);
        }
        return $handler->handle($request);
    }

    protected function inventoryValid($params, ServerRequestInterface $request): ServerRequestInterface
    {
        if (!isset($params[0])) {
            $params = [$params];
        }

        $idList = array_column($params, 'id');
        $tempIdList = [];
        $items = $this->itemService->getItemList(['type' => 'product', 'id' => $idList]);
        $itemsList = $items['data']['list'];
        $outOfStockItems = [];

        foreach ($itemsList as $item) {
            $tempIdList[] = $item['id'];
            $metaList = $item['meta'] ?? [];
            $inventoryMeta = array_filter($metaList, fn($meta) => $meta['meta_key'] === 'inventory');
            $inventoryValue = reset($inventoryMeta)['meta_value'] ?? 0;
            foreach ($params as $object) {
                if ($item['id'] === $object['id'] && $inventoryValue < $object['count']) {
                    $outOfStockItems[] = $item['title'];
                }
            }
        }

        if (count($idList) != count($tempIdList)) {
            $this->InventoryResult = [
                'status' => false,
                'code' => StatusCodeInterface::STATUS_FORBIDDEN,
                'message' => sprintf("Can't find items with id: %s", implode(', ', array_diff($idList, $tempIdList))),
            ];
            return $request;
        }

        if (!empty($outOfStockItems)) {
            $this->InventoryResult = [
                'status' => false,
                'code' => StatusCodeInterface::STATUS_FORBIDDEN,
                'message' => sprintf("Out of stock for the following items: %s", implode(', ', $outOfStockItems)),
            ];
            return $request;
        }

        // Add the validated cart data to the request as an attribute
        return $request->withAttribute('cart', $params);
    }

    protected function reserveIsValid($params)
    {
        // Reservation validation logic here
    }
    private function updateValid(object|array|null $params, ServerRequestInterface $request): ServerRequestInterface
    {
        if (!isset($params[0])) {
            $params = [$params];
        }
        $items = $this->itemService->getItem('cart-' . $request->getAttribute('account')['id'], 'slug');
        $result = [];

        // cart is empty and this action is as add-cart
        if (empty($items)) {
            $result = $params;
        } else {
            $carts = $items['cart'];
            $combined = array_merge($params, $carts);
            foreach ($combined as $item) {
                $id = $item['id'];
                $count = $item['count'];
                if (isset($result[$id])) {
                    $result[$id]['count'] += $count;
                } else {
                    $result[$id] = [
                        'id' => $id,
                        'count' => $count
                    ];
                }
            }
        }
        return $this->inventoryValid(array_values($result), $request);
    }

    private function removeValid(object|array|null $params, ServerRequestInterface $request): ServerRequestInterface
    {
        if (!isset($params[0])) {
            $params = [$params];
        }
        $result = [];
        $items = $this->itemService->getItem('cart-' . $request->getAttribute('account')['id'], 'slug');

        if (!empty($items)) {
            $idList = array_column($params, 'id');
            $carts = $items['cart'];
            $result = array_filter($carts,fn($item)=>!in_array($item['id'], $idList));
        }
        return $request->withAttribute('cart', $result);
    }

    //for check inventory on order module [create order handler]
    private function physicalOrderValid(object|array|null $params, ServerRequestInterface $request): ServerRequestInterface
    {
        if(!isset($params['address'])||!isset($params['cart_id'])) {
            $this->InventoryResult = [
                'status' => false,
                'code' => StatusCodeInterface::STATUS_FORBIDDEN,
                'message' => sprintf('%s,%s not set!',isset($params['address'])?'':'Address',isset($params['cart_id'])?'':'Cart'),
            ];
            return $request;
        }
        $items = $this->itemService->getItem($params['cart_id']);
        if(empty($items)){
            $this->InventoryResult = [
                'status' => false,
                'code' => StatusCodeInterface::STATUS_FORBIDDEN,
                'message' => 'Cart not found!',
            ];
            return $request;
        }
        return $this->inventoryValid($items['cart'],$request);
    }

}
