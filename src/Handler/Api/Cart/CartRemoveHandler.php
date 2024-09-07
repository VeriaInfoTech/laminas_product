<?php

namespace Product\Handler\Api\Cart;

use Product\Service\CartService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CartRemoveHandler implements RequestHandlerInterface
{  /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var CartService */
    protected CartService $cartService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        CartService              $cartService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->cartService = $cartService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute("account");
        $params = [
            'cart' => $request->getAttribute("cart"),
        ];
        $result = $this->cartService->updateCart($params, $account);
        return new JsonResponse(
            [
                'result' => true,
                'data' => $result,
                'error' => [],
            ],
        );
    }
}