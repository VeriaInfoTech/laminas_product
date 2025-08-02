<?php

namespace Product\Handler\Api\Favorite;

use Product\Service\CartService;
use Product\Service\ProductService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FavoriteListHandler implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var ProductService */
    protected ProductService $ProductService;

    /** @var ProductService */
    protected CartService $cartService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        ProductService $ProductService,
        CartService $cartService
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->ProductService = $ProductService;
        $this->cartService = $cartService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute("account");
        $favorite = $this->cartService->getFavorite($account);

        $request = $request->getParsedBody();
        $type = $request['item_type']??'product';
        $list =[];
        if(isset($favorite[$type])){
            if(!empty($favorite[$type])){
                $list = $this->ProductService->getItemList(['type' => 'product', 'limit' => 10, 'id' => $favorite[$type]], $account)['data']['list'];
            }
        }


        return new JsonResponse(
            [
                'result' => true,
                'data'   => $list,
                'error'  => [],
            ],
        );
    }
}