<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ShopFlow\Models\Task;
use ShopFlow\Models\Project;
use Doctrine\ORM\EntityManager;

class TaskController
{
    private EntityManager $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/database.php';
        $this->em = $this->em();
    }

    public function index(Request $request, Response $response, array $args): Response
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
        
        $tasks = array_map(fn($t) => $t->toArray(), $project->getTasks()->toArray());
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $tasks
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response, array $args): Response
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
        
        $task = new Task();
        $task->setTitle($data['title'] ?? '')
            ->setDescription($data['description'] ?? null)
            ->setStatus($data['status'] ?? 'backlog')
            ->setPriority($data['priority'] ?? 'medium')
            ->setProject($project);
        
        if (isset($data['assignee_id'])) {
            $task->setAssigneeId($data['assignee_id']);
        }
        
        if (isset($data['story_points'])) {
            $task->setStoryPoints($data['story_points']);
        }
        
        $this->em->persist($task);
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $task->toArray()
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $task = $this->em->find(Task::class, $args['id']);
        
        if (!$task) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Task not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        if (isset($data['title'])) $task->setTitle($data['title']);
        if (isset($data['description'])) $task->setDescription($data['description']);
        if (isset($data['status'])) $task->setStatus($data['status']);
        if (isset($data['priority'])) $task->setPriority($data['priority']);
        if (isset($data['assignee_id'])) $task->setAssigneeId($data['assignee_id']);
        if (isset($data['story_points'])) $task->setStoryPoints($data['story_points']);
        
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $task->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function move(Request $request, Response $response, array $args): Response
    {
        $task = $this->em->find(Task::class, $args['id']);
        
        if (!$task) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Task not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        if (isset($data['column'])) {
            $task->setStatus($data['column']);
        }
        
        if (isset($data['position'])) {
            $task->setPosition($data['position']);
        }
        
        if (isset($data['assignee_id'])) {
            $task->setAssigneeId($data['assignee_id']);
        }
        
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $task->toArray()
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $task = $this->em->find(Task::class, $args['id']);
        
        if (!$task) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Task not found'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        
        $this->em->remove($task);
        $this->em->flush();
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
