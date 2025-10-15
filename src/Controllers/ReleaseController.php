<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ShopFlow\Models\Release;
use ShopFlow\Services\GitService;
use Doctrine\ORM\EntityManager;

class ReleaseController
{
    private EntityManager $em;
    private GitService $gitService;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/database.php';
        $this->em = $this->em();
        $this->gitService = new GitService();
    }

    public function index(Request $request, Response $response): Response
    {
        $releases = $this->em->getRepository(Release::class)->findAll();
        
        $data = array_map(fn($r) => $r->toArray(), $releases);
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $data
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $release = new Release();
        $release->setVersion($data['version'] ?? '')
            ->setName($data['name'] ?? '')
            ->setNotes($data['notes'] ?? null)
            ->setProjectId($data['project_id'] ?? 0)
            ->setBranch($data['branch'] ?? 'main');
        
        $this->em->persist($release);
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $release->toArray()
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $release = $this->em->find(Release::class, $args['id']);
        
        if (!$release) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Release not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $release->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deploy(Request $request, Response $response, array $args): Response
    {
        $release = $this->em->find(Release::class, $args['id']);
        
        if (!$release) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Release not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        try {
            // Simulate deployment
            $release->setStatus('deployed');
            $release->setDeployedAt(new \DateTime());
            $release->setGitTag('v' . $release->getVersion());
            
            $this->em->flush();
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Release deployed successfully',
                'data' => $release->toArray()
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Deployment failed: ' . $e->getMessage()
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    public function rollback(Request $request, Response $response, array $args): Response
    {
        $release = $this->em->find(Release::class, $args['id']);
        
        if (!$release) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Release not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $release->setStatus('rolled_back');
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Release rolled back successfully',
            'data' => $release->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
