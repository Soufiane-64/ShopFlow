<?php

namespace ShopFlow\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ShopFlow\Models\User;
use Doctrine\ORM\EntityManager;

class AuthService
{
    private EntityManager $em;
    private string $jwtSecret;
    private int $jwtExpiration;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/database.php';
        $this->em = $this->em();
        $this->jwtSecret = $_ENV['JWT_SECRET'];
        $this->jwtExpiration = (int)$_ENV['JWT_EXPIRATION'];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if (!$user || !$user->verifyPassword($password)) {
            throw new \Exception('Invalid credentials');
        }
        
        return [
            'user' => $user->toArray(),
            'token' => $this->generateToken($user),
            'refresh_token' => $this->generateRefreshToken($user)
        ];
    }

    public function register(string $name, string $email, string $password): array
    {
        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if ($existing) {
            throw new \Exception('Email already registered');
        }
        
        $user = new User();
        $user->setName($name)
            ->setEmail($email)
            ->setPassword($password);
        
        $this->em->persist($user);
        $this->em->flush();
        
        return [
            'user' => $user->toArray(),
            'token' => $this->generateToken($user),
            'refresh_token' => $this->generateRefreshToken($user)
        ];
    }

    public function refreshToken(string $refreshToken): array
    {
        try {
            $decoded = JWT::decode($refreshToken, new Key($this->jwtSecret, 'HS256'));
            
            $user = $this->em->find(User::class, $decoded->user_id);
            
            if (!$user) {
                throw new \Exception('User not found');
            }
            
            return [
                'token' => $this->generateToken($user),
                'refresh_token' => $this->generateRefreshToken($user)
            ];
        } catch (\Exception $e) {
            throw new \Exception('Invalid refresh token');
        }
    }

    private function generateToken(User $user): string
    {
        $payload = [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => time() + $this->jwtExpiration
        ];
        
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    private function generateRefreshToken(User $user): string
    {
        $payload = [
            'user_id' => $user->getId(),
            'iat' => time(),
            'exp' => time() + (int)$_ENV['JWT_REFRESH_EXPIRATION']
        ];
        
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    public function verifyToken(string $token): object
    {
        return JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
    }
}
