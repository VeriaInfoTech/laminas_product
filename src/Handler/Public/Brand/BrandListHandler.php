<?php

namespace Product\Handler\Public\Brand;

use Product\Service\BrandService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BrandListHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var BrandService */
    protected BrandService $brandService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        BrandService              $brandService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->brandService = $brandService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get request body
        $requestBody = $request->getParsedBody();
        $requestBody['status'] = 1;
        $result = $this->brandService->getBrandList($requestBody);

        return new JsonResponse($result);
    }
}