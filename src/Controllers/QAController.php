<?php

namespace ShopFlow\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QAController
{
    public function checklists(Request $request, Response $response): Response
    {
        $checklists = [
            'ecommerce_essentials' => [
                'Warenkorb-Funktionalität',
                'Checkout-Prozess (alle Payment-Provider)',
                'Responsive Design (Mobile/Tablet/Desktop)',
                'SEO-Meta-Tags',
                'Performance (PageSpeed > 90)',
                'Security (SQL-Injection, XSS)',
                'DSGVO-Konformität',
                'Barrierefreiheit (WCAG 2.1)'
            ],
            'shopware_specific' => [
                'Plugin-Kompatibilität prüfen',
                'Theme-Anpassungen testen',
                'Admin-Panel Funktionalität',
                'API-Endpoints validieren',
                'Datenbank-Migrationen',
                'Cache-Clearing funktioniert'
            ]
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $checklists
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createBug(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        // In a real app, this would save to database
        $bug = [
            'id' => rand(1000, 9999),
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'severity' => $data['severity'] ?? 'medium',
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $bug
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function reports(Request $request, Response $response): Response
    {
        $reports = [
            'total_tests' => 156,
            'passed' => 142,
            'failed' => 8,
            'skipped' => 6,
            'coverage' => 87.5,
            'last_run' => date('Y-m-d H:i:s')
        ];
        
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $reports
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
