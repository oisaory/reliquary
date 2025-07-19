# Development Guidelines for Reliquary Project

This document provides essential information for developers working on the Reliquary project.

## Build/Configuration Instructions

### Docker Setup

The project uses Docker for local development with the following services:

- PHP 8.2 FPM
- Nginx web server
- PostgreSQL database
- Mailpit for email testing

#### Requirements

- Docker
- Docker Compose

#### Getting Started

1. Clone the repository
2. Build and start the Docker containers:

```bash
docker compose up -d
```

3. Install Composer dependencies:

```bash
docker compose exec php composer install
```

4. Access the application in your browser:

```
http://localhost:8080
```

#### Services

- **Web Server**: http://localhost:8080
- **Database**: PostgreSQL (accessible via port 5432)
- **Mail Server**: Mailpit (accessible via http://localhost:8025)

#### Common Commands

- Start the containers: `docker compose up -d`
- Stop the containers: `docker compose down`
- View logs: `docker compose logs -f`
- Access PHP container: `docker compose exec php bash`
- Run Symfony commands: `docker compose exec php bin/console <command>`

#### Configuration

- PHP configuration can be modified in `docker/php/php.ini`
- Nginx configuration can be modified in `docker/nginx/default.conf`
- Database configuration can be modified in `.env` file or by setting environment variables

### Environment Configuration

The project uses Symfony's environment system:

- `.env` contains default values for environment variables
- `.env.local` (not committed) should be used for local overrides
- `.env.$APP_ENV` for environment-specific defaults
- `.env.$APP_ENV.local` for environment-specific overrides

## Testing Information

### Testing Setup

The project uses PHPUnit for testing. Tests are located in the `tests/` directory and follow the same namespace structure as the source code, but with the `App\Tests` namespace.

### Running Tests

To run all tests:

```bash
php bin/phpunit
```

To run a specific test file:

```bash
php bin/phpunit tests/Path/To/TestFile.php
```

To run tests with a specific filter:

```bash
php bin/phpunit --filter=testMethodName
```

### Creating Tests

#### Unit Tests

Unit tests should extend `PHPUnit\Framework\TestCase` and test individual components in isolation. Example:

```php
<?php

namespace App\Tests\Entity;

use App\Entity\Relic;
use PHPUnit\Framework\TestCase;

class RelicTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $relic = new Relic();

        $location = 'Vatican City';
        $relic->setLocation($location);
        $this->assertEquals($location, $relic->getLocation());
    }
}
```

#### Functional Tests

Functional tests should extend `Symfony\Bundle\FrameworkBundle\Test\WebTestCase` and test the integration of components. Example:

```php
<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelicControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/relic');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Relics');
    }
}
```

Note: Functional tests may require database setup and fixtures to work properly.

### Test Database

For tests that require a database, you should:

1. Configure a test database in your `.env.test` file
2. Create the test database schema:

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

3. Load fixtures if needed:

```bash
php bin/console doctrine:fixtures:load --env=test
```

## Code Style and Development Practices

### Project Structure

The project follows the standard Symfony directory structure:

- `src/` - Application source code
  - `Controller/` - Controllers that handle HTTP requests
  - `Entity/` - Doctrine ORM entities
  - `Repository/` - Doctrine repositories for database queries
  - `Form/` - Form types for handling form submissions
  - `Security/` - Security-related classes
- `templates/` - Twig templates for rendering views
- `public/` - Publicly accessible files
- `config/` - Configuration files
- `tests/` - Test files

### Coding Standards

- Follow PSR-1, PSR-2, and PSR-4 standards
- Use type hints for method parameters and return types
- Use final classes where appropriate
- Use constructor property promotion for simple classes
- Use attributes for Doctrine ORM mappings and Symfony routing

### Git Workflow

- Commit directly to main branch
- Write descriptive commit messages
- Keep commits small and focused
- Get code review through pair programming

### Symfony Best Practices

- Follow the [Symfony Best Practices](https://symfony.com/doc/current/best_practices.html)
- Use dependency injection instead of static methods
- Use services for business logic
- Keep controllers thin
- Use Symfony forms for handling form submissions
- Use Symfony validators for validation

### JavaScript Development

- Use Stimulus for all JavaScript functionality
- Do not use embedded JavaScript directly in templates
- Create Stimulus controllers in the `assets/controllers/` directory
- Follow the naming convention: `feature_controller.js` for controller files
- Use data attributes to connect HTML elements to Stimulus controllers:
  - `data-controller="feature"` to initialize a controller
  - `data-feature-target="element"` to define targets
  - `data-action="feature#method"` to bind actions
- See existing controllers like `map_controller.js` and `responsive_frame_controller.js` as examples

### Templates

- Use the relics templates as the standard for new templates
- Follow the same structure and styling conventions as found in the relic templates
- Maintain consistency in UI components and layout across the application

### Database Management

- Use `doctrine:schema:update --force` instead of migrations for schema updates:
  ```bash
  php bin/console doctrine:schema:update --force
  ```
- This approach is simpler for development and ensures the database schema always matches your entity definitions

## Implementation Planning

When implementing new features or making significant changes to the Reliquary application, you should create a detailed implementation plan:

### Implementation Plan Structure

- Follow the structure outlined in the [Implementation Plan Structure](../Plans/implementation_plan_structure.md) document
- This document provides a template and guidelines for creating comprehensive implementation plans
- All implementation plans should be stored in the `Plans/` directory

### When to Create an Implementation Plan

- For new features that affect multiple components
- For significant refactoring efforts
- For changes to core functionality
- For features that will be implemented over multiple sprints

### Example Implementation Plan

- See the [Translation Implementation Plan](../Plans/translation_implementation_plan.md) for a complete example
- Use this as a reference when creating your own implementation plans

## Production Configuration

### Docker Production Setup

The project uses Docker for production deployment with the following services:

- PHP 8.2 FPM (production-optimized image)
- Nginx web server with SSL support
- PostgreSQL database

#### Production Environment Variables

Create a `.env.prod` file with the following variables:

```
DOCKER_REGISTRY=ghcr.io/your-github-username
IMAGE_TAG=latest
APP_SECRET=your-app-secret
POSTGRES_DB=reliquary
POSTGRES_USER=app
POSTGRES_PASSWORD=your-secure-password
NGINX_SSL_PORT=443
```

#### Deployment Commands

```bash
# Pull the latest images and start the containers
docker compose -f compose.prod.yaml --env-file .env.prod pull
docker compose -f compose.prod.yaml --env-file .env.prod up -d
```

#### Production Configuration Details

- The production setup uses pre-built Docker images from a registry
- APP_ENV is set to 'prod' for optimized performance
- Nginx is configured to serve HTTPS on port 443
- Database data is persisted using Docker volumes

### SSL/TLS Configuration

The Nginx container is configured to serve HTTPS with SSL/TLS support. For production:

1. **Replace Self-Signed Certificates**
   - Mount your SSL certificates into the container:
     ```yaml
     volumes:
       - ./ssl/your-cert.crt:/etc/nginx/ssl/nginx.crt
       - ./ssl/your-key.key:/etc/nginx/ssl/nginx.key
     ```

2. **Consider Let's Encrypt** for automatic certificate management

## CI Workflow

The project includes a GitHub Actions workflow for continuous integration and deployment:

### Workflow Triggers

The CI workflow is triggered on:
- Pushes to the main branch
- Pull requests to the main branch

### CI Process

1. **Build and Test**
   - Builds the PHP and Nginx Docker images
   - Uses Docker Buildx for efficient builds
   - Utilizes GitHub Actions cache for faster builds

2. **Docker Image Publishing**
   - Pushes images to GitHub Container Registry (ghcr.io)
   - Images are tagged with:
     - Branch name
     - PR number (for pull requests)
     - Semantic version (when tagged)
     - Short SHA

3. **Deployment**
   - Images are available at `ghcr.io/your-github-username/reliquary-php` and `ghcr.io/your-github-username/reliquary-nginx`
   - Manual deployment is required on the production server

### Maintenance

#### Updating the Application

```bash
# Pull the latest images
docker compose -f compose.prod.yaml --env-file .env.prod pull

# Restart the containers
docker compose -f compose.prod.yaml --env-file .env.prod up -d
```

#### Monitoring and Troubleshooting

```bash
# View logs for all services
docker compose -f compose.prod.yaml logs

# View logs for a specific service
docker compose -f compose.prod.yaml logs php
```
