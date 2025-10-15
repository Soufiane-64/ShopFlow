<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ShopFlow\Models\Project;
use Doctrine\ORM\EntityManager;

class ProjectController
{
    private EntityManager $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/database.php';
        $this->em = $this->em();
    }

    public function index(Request $request, Response $response): Response
    {
        $projects = $this->em->getRepository(Project::class)->findAll();
        
        $data = array_map(fn($p) => $p->toArray(), $projects);
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $data
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $project = new Project();
        $project->setName($data['name'] ?? '')
            ->setType($data['type'] ?? 'shopware6')
            ->setStatus($data['status'] ?? 'planning')
            ->setDescription($data['description'] ?? null)
            ->setClientId($data['client_id'] ?? null);
        
        if (!empty($data['start_date'])) {
            $project->setStartDate(new \DateTime($data['start_date']));
        }
        
        if (!empty($data['deadline'])) {
            $project->setDeadline(new \DateTime($data['deadline']));
        }
        
        if (!empty($data['git_repository'])) {
            $project->setGitRepository($data['git_repository']);
        }
        
        $this->em->persist($project);
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $project->toArray()
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $project = $this->em->find(Project::class, $args['id']);
        
        if (!$project) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Project not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $project->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $project = $this->em->find(Project::class, $args['id']);
        
        if (!$project) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Project not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        if (isset($data['name'])) $project->setName($data['name']);
        if (isset($data['status'])) $project->setStatus($data['status']);
        if (isset($data['progress'])) $project->setProgress($data['progress']);
        if (isset($data['description'])) $project->setDescription($data['description']);
        
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $project->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $project = $this->em->find(Project::class, $args['id']);
        
        if (!$project) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Project not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $this->em->remove($project);
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
