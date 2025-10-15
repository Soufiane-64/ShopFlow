<?php

namespace ShopFlow\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');
        
        if ($request->getMethod() === 'POST' || $request->getMethod() === 'PUT') {
            if (strpos($contentType, 'application/json') === false) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Content-Type must be application/json'
                ]));
                
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }
        }
        
        return $handler->handle($request);
    }
}
