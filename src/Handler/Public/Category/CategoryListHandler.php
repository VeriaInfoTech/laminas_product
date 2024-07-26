<?php

namespace Product\Handler\Public\Category;

use Laminas\Diactoros\Response\JsonResponse;
use Product\Service\CategoryService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CategoryListHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var CategoryService */
    protected CategoryService $categoryService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        CategoryService          $categoryService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->categoryService = $categoryService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Get request body
        $requestBody = $request->getParsedBody();
        $requestBody['status'] = 1;
        $requestBody['key'] = 'category';
        $result = [
            'result' => true,
            'data' => [
                "list" => $this->categoryService->getCategoryList($requestBody),
            ],
            'paginator' => [
                'count' => null,
                'limit' => null,
                'page'  => null,
            ],
            'error' => [],
        ];

        return new JsonResponse($result);
    }
}