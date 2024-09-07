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
    ) {
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
            case 'inventory':
                $request = $this->inventoryValid($parsedBody, $request);
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
                if ($item['id'] === $object['id'] && $object['count'] > $inventoryValue) {
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
}
