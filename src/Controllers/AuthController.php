<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ShopFlow\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $result = $this->authService->login(
                $data['email'] ?? '',
                $data['password'] ?? ''
            );
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $result
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $result = $this->authService->register(
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? ''
            );
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $result
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }

    public function refresh(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $result = $this->authService->refreshToken($data['refresh_token'] ?? '');
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $result
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}
