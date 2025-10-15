<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClientController
{
    public function requirements(Request $request, Response $response): Response
    {
        // Mock data - in real app, fetch from database
        $requirements = [
            [
                'id' => 1,
                'title' => 'Neue Produktfilter',
                'description' => 'Erweiterte Filteroptionen für Produktkatalog',
                'status' => 'in_review',
                'priority' => 'high',
                'estimated_hours' => 40
            ],
            [
                'id' => 2,
                'title' => 'Newsletter Integration',
                'description' => 'Mailchimp Integration für Newsletter',
                'status' => 'approved',
                'priority' => 'medium',
                'estimated_hours' => 16
            ]
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $requirements
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createRequirement(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $requirement = [
            'id' => rand(1000, 9999),
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'status' => 'pending',
            'priority' => $data['priority'] ?? 'medium',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $requirement
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function progress(Request $request, Response $response, array $args): Response
    {
        $projectId = $args['projectId'];
        
        $progress = [
            'project_id' => $projectId,
            'overall_progress' => 67,
            'milestones' => [
                ['name' => 'Design Phase', 'status' => 'completed', 'progress' => 100],
                ['name' => 'Development', 'status' => 'in_progress', 'progress' => 75],
                ['name' => 'Testing', 'status' => 'pending', 'progress' => 0],
                ['name' => 'Deployment', 'status' => 'pending', 'progress' => 0]
            ],
            'next_milestone' => 'Testing Phase',
            'estimated_completion' => date('Y-m-d', strtotime('+2 weeks'))
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $progress
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
