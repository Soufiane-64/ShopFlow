<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "Seeding users...\n";
    $password = password_hash('shopflow2024', PASSWORD_BCRYPT);
    $pdo->exec("
        INSERT IGNORE INTO users (id, name, email, password, role, created_at) VALUES
        (1, 'Admin User', 'admin@shopflow.dev', '{$password}', 'admin', NOW()),
        (2, 'John Developer', 'john@shopflow.dev', '{$password}', 'user', NOW()),
        (3, 'Jane Designer', 'jane@shopflow.dev', '{$password}', 'user', NOW()),
        (4, 'Mike Tester', 'mike@shopflow.dev', '{$password}', 'user', NOW())
    ");
    
    echo "Seeding clients...\n";
    $pdo->exec("
        INSERT IGNORE INTO clients (id, name, email, company, phone, created_at) VALUES
        (1, 'Max Mustermann', 'max@example.com', 'Mustermann GmbH', '+49 123 456789', NOW()),
        (2, 'Anna Schmidt', 'anna@example.com', 'Schmidt E-Commerce', '+49 987 654321', NOW())
    ");
    
    echo "Seeding projects...\n";
    $pdo->exec("
        INSERT IGNORE INTO projects (id, name, type, status, progress, description, client_id, start_date, deadline, git_repository, created_at) VALUES
        (1, 'Shop-Relaunch 2025', 'shopware6', 'in_progress', 45, 'Complete shop redesign with new features', 1, '2025-01-01', '2025-12-31', 'https://github.com/example/shop-relaunch', NOW()),
        (2, 'Mobile App Development', 'shopware6', 'planning', 10, 'Native mobile app for iOS and Android', 2, '2025-03-01', '2025-09-30', 'https://github.com/example/mobile-app', NOW()),
        (3, 'Payment Integration', 'shopware6', 'completed', 100, 'Integration of new payment providers', 1, '2024-10-01', '2024-12-31', 'https://github.com/example/payment', NOW())
    ");
    
    echo "Seeding tasks...\n";
    $pdo->exec("
        INSERT IGNORE INTO tasks (title, description, status, priority, position, assignee_id, story_points, project_id, created_at) VALUES
        ('Design Homepage', 'Create mockups for new homepage', 'in-progress', 'high', 1, 3, 8, 1, NOW()),
        ('Implement Product Filter', 'Add advanced filtering options', 'backlog', 'medium', 2, 2, 5, 1, NOW()),
        ('Setup CI/CD Pipeline', 'Configure GitHub Actions', 'done', 'high', 3, 2, 3, 1, NOW()),
        ('Write API Documentation', 'Document all REST endpoints', 'review', 'medium', 4, 4, 5, 1, NOW()),
        ('Mobile UI Design', 'Design mobile app screens', 'backlog', 'high', 1, 3, 13, 2, NOW())
    ");
    
    echo "Seeding releases...\n";
    $pdo->exec("
        INSERT IGNORE INTO releases (version, name, notes, status, project_id, git_tag, branch, deployed_at, created_at) VALUES
        ('1.0.0', 'Initial Release', 'First production release', 'deployed', 3, 'v1.0.0', 'main', NOW(), NOW()),
        ('1.1.0', 'Feature Update', 'Added new payment methods', 'deployed', 3, 'v1.1.0', 'main', NOW(), NOW()),
        ('2.0.0', 'Major Redesign', 'Complete UI overhaul', 'planned', 1, NULL, 'develop', NULL, NOW())
    ");
    
    echo "Seeding project team members...\n";
    $pdo->exec("
        INSERT IGNORE INTO project_team_members (project_id, user_id) VALUES
        (1, 2), (1, 3), (1, 4),
        (2, 2), (2, 3),
        (3, 2)
    ");
    
    echo "Database seeded successfully!\n";
    
} catch (PDOException $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
