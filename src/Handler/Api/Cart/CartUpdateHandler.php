<?php

namespace Product\Handler\Api\Cart;

use Product\Service\ProductService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CartUpdateHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var ProductService */
    protected ProductService $productService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        ProductService              $productService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->productService = $productService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get request body
        $requestBody = $request->getParsedBody();
        $requestBody['status'] = 1;
        $result = $this->productService->getItemUpdate($requestBody);

        return new JsonResponse($result);
    }
}