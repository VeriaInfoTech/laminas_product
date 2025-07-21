<?php

namespace Product\Handler\Api\Favorite;

use Product\Service\ProductService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FavoriteListHandler  implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var ProductService */
    protected ProductService $ProductService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        ProductService              $ProductService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->ProductService = $ProductService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute("account");
        $params = [
            'cart' => $request->getAttribute("cart"),
        ];
        $result = $this->ProductService->getItemList(['type'=>'product','limit'=>10], $account);
        return new JsonResponse(
            [
                'result' => true,
                'data' => $result,
                'error' => [],
            ],
        );
    }
}