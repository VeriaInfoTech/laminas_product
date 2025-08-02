<?php

namespace Product\Handler\Api\Comment;

use Product\Service\CommentService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CommentAddHandler  implements RequestHandlerInterface
{
    /** @var ResponseFactoryInterface */
    protected ResponseFactoryInterface $responseFactory;

    /** @var StreamFactoryInterface */
    protected StreamFactoryInterface $streamFactory;

    /** @var CommentService */
    protected CommentService $commentService;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface   $streamFactory,
        CommentService              $commentService
    )
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->commentService = $commentService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute("account");
        $request = $request->getParsedBody();
        $result = $this->commentService->addComment($request,$account);
        return new JsonResponse(
            [
                'result' => true,
                'data' => $result,
                'error' => [],
            ],
        );
    }
}