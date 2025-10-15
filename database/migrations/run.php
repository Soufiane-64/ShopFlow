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
    
    echo "Creating users table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'user',
            created_at DATETIME NOT NULL,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Creating clients table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            company VARCHAR(255),
            phone VARCHAR(50),
            created_at DATETIME NOT NULL,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Creating projects table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(50) NOT NULL,
            status VARCHAR(50) DEFAULT 'planning',
            progress INT DEFAULT 0,
            description TEXT,
            client_id INT,
            start_date DATE,
            deadline DATE,
            git_repository VARCHAR(255),
            created_at DATETIME NOT NULL,
            INDEX idx_status (status),
            INDEX idx_client (client_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Creating tasks table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            status VARCHAR(50) DEFAULT 'backlog',
            priority VARCHAR(20) DEFAULT 'medium',
            position INT DEFAULT 0,
            assignee_id INT,
            story_points INT,
            project_id INT NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_status (status),
            INDEX idx_project (project_id),
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Creating releases table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS releases (
            id INT AUTO_INCREMENT PRIMARY KEY,
            version VARCHAR(50) NOT NULL,
            name VARCHAR(255) NOT NULL,
            notes TEXT,
            status VARCHAR(50) DEFAULT 'planned',
            project_id INT NOT NULL,
            git_tag VARCHAR(255),
            branch VARCHAR(255),
            deployed_at DATETIME,
            created_at DATETIME NOT NULL,
            INDEX idx_project (project_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "Creating project_team_members table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS project_team_members (
            project_id INT NOT NULL,
            user_id INT NOT NULL,
            PRIMARY KEY (project_id, user_id),
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "All migrations completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
