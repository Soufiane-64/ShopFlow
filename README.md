# ShopFlow

ShopFlow is a lightweight project management and release-tracking web application built with PHP (Slim framework) and a small Vue.js frontend. It helps teams manage clients, projects, releases, tasks, and QA workflows.

## Table of contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Local development](#local-development)
- [Running tests](#running-tests)
- [Project structure](#project-structure)
- [Useful files](#useful-files)
- [Contributing](#contributing)
- [License](#license)

## Features

- Authentication and user management
- Client and project management
- Releases and tasks tracking
- Basic notifications and documentation utilities
- Git and Shopware integration helpers

## Tech stack

- PHP (Slim framework)
- Composer for PHP dependencies
- Vue.js for frontend (single-file components under `src/frontend`)
- Docker and docker-compose (optional) for local development
- Vite and Tailwind CSS for frontend tooling

## Prerequisites

- PHP 8.x
- Composer
- Node.js and npm or yarn (for frontend tooling)
- Docker & docker-compose (recommended for easy local setup)

## Local development

The project includes a `docker-compose.yml` and a `Dockerfile` for local development. You can run the full application using Docker.

Basic flow (using Docker):

```powershell
# build and start services
docker-compose up --build -d

# view logs (example)
docker-compose logs -f

# run migrations and seeds (if provided)
# see scripts in database/migrations/run.php and database/seeds/run.php
```

Without Docker, install PHP dependencies and run a local server:

```powershell
composer install
# serve using PHP's built-in server (adjust public path as needed)
php -S localhost:8000 -t public
```

Frontend (development):

```powershell
cd src/frontend
npm install
npm run dev
```

Build frontend for production:

```powershell
npm run build
```

## Running tests

The repository includes PHPUnit configuration. Run tests with:

```powershell
composer test
# or
vendor/bin/phpunit
```

## Project structure

- `src/Controllers` - HTTP controllers
- `src/Models` - Domain models (User, Client, Project, Release, Task)
- `src/Services` - Business logic and integrations (Git, Shopware, Notifications)
- `src/Helpers` - Utility helpers
- `src/Middleware` - Slim middleware (auth, validation)
- `src/frontend` - Vue.js frontend application
- `public` - Web server document root
- `config` - Application configuration and routing
- `database` - Migrations and seeders
- `docker` - Dockerfiles and container configuration

## Useful files

- `composer.json` - PHP dependencies and scripts
- `package.json` - Frontend dependencies and scripts
- `docker-compose.yml` - Docker services configuration
- `phpunit.xml` - PHPUnit configuration
- `vite.config.js` - Frontend build tooling

## Contributing

If you'd like to contribute, please open an issue first to discuss the change. Fork the repository, create a feature branch, add tests, and open a pull request.

## License

This project does not include a license file in the repository. Add a license file if you'd like to specify the terms for reuse.


---

Notes:
- This README is a starting point. Update the commands and documentation to match your preferred local workflow and any custom scripts in `composer.json` or `package.json`.
