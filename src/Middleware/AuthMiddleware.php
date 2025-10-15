<?php

namespace ShopFlow\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ShopFlow\Services\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Authorization header missing'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
        
        $token = str_replace('Bearer ', '', $authHeader);
        
        try {
            $decoded = $this->authService->verifyToken($token);
            
            // Add user info to request attributes
            $request = $request->withAttribute('user', $decoded);
            
            return $handler->handle($request);
        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Invalid or expired token'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}
