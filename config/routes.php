<?php

use Slim\App;
use ShopFlow\Controllers\ProjectController;
use ShopFlow\Controllers\ReleaseController;
use ShopFlow\Controllers\QAController;
use ShopFlow\Controllers\ClientController;
use ShopFlow\Controllers\AuthController;
use ShopFlow\Controllers\TaskController;
use ShopFlow\Middleware\AuthMiddleware;

return function (App $app) {
    // Public routes
    $app->post('/api/auth/login', [AuthController::class, 'login']);
    $app->post('/api/auth/register', [AuthController::class, 'register']);
    $app->post('/api/auth/refresh', [AuthController::class, 'refresh']);

    // Protected routes
    $app->group('/api', function ($group) {
        // Projects
        $group->get('/projects', [ProjectController::class, 'index']);
        $group->post('/projects', [ProjectController::class, 'create']);
        $group->get('/projects/{id}', [ProjectController::class, 'show']);
        $group->put('/projects/{id}', [ProjectController::class, 'update']);
        $group->delete('/projects/{id}', [ProjectController::class, 'delete']);

        // Tasks
        $group->get('/projects/{id}/tasks', [TaskController::class, 'index']);
        $group->post('/projects/{id}/tasks', [TaskController::class, 'create']);
        $group->put('/tasks/{id}', [TaskController::class, 'update']);
        $group->delete('/tasks/{id}', [TaskController::class, 'delete']);
        $group->post('/tasks/{id}/move', [TaskController::class, 'move']);

        // Releases
        $group->get('/releases', [ReleaseController::class, 'index']);
        $group->post('/releases', [ReleaseController::class, 'create']);
        $group->get('/releases/{id}', [ReleaseController::class, 'show']);
        $group->post('/releases/{id}/deploy', [ReleaseController::class, 'deploy']);
        $group->post('/releases/{id}/rollback', [ReleaseController::class, 'rollback']);

        // QA
        $group->get('/qa/checklists', [QAController::class, 'checklists']);
        $group->post('/qa/bugs', [QAController::class, 'createBug']);
        $group->get('/qa/reports', [QAController::class, 'reports']);

        // Client Portal
        $group->get('/client/requirements', [ClientController::class, 'requirements']);
        $group->post('/client/requirements', [ClientController::class, 'createRequirement']);
        $group->get('/client/progress/{projectId}', [ClientController::class, 'progress']);
    })->add(AuthMiddleware::class);
};
