# Code Style and Development Practices

## Project Structure

The project follows the standard Symfony directory structure with some additional directories:

- `src/` - Application source code
  - `Command/` - Console commands
  - `Controller/` - Controllers that handle HTTP requests
  - `Entity/` - Doctrine ORM entities
  - `Enum/` - PHP enumerations
  - `EventListener/` - Event listeners
  - `EventSubscriber/` - Event subscribers
  - `Form/` - Form types for handling form submissions
  - `Repository/` - Doctrine repositories for database queries
  - `Security/` - Security-related classes
  - `Service/` - Application services
  - `Twig/` - Twig extensions and runtime classes
- `templates/` - Twig templates for rendering views
- `public/` - Publicly accessible files
- `config/` - Configuration files
- `tests/` - Test files
- `translations/` - Translation files

## Coding Standards

- Follow PSR-1, PSR-2, and PSR-4 standards
- Use type hints for method parameters and return types
- Use final classes where appropriate
- Use constructor property promotion for simple classes
- Use attributes for Doctrine ORM mappings and Symfony routing

## Git Workflow

- Commit directly to main branch
- Write descriptive commit messages
- Keep commits small and focused
- Get code review through pair programming

## Symfony Best Practices

- Follow the [Symfony Best Practices](https://symfony.com/doc/current/best_practices.html)
- Use dependency injection instead of static methods
- Use services for business logic
- Keep controllers thin
- Use Symfony forms for handling form submissions
- Use Symfony validators for validation

## Front-end Architecture

The project uses Symfony's Asset Mapper and importmap for front-end asset management:

- Assets are defined in `importmap.php` instead of using a traditional build system
- JavaScript modules are loaded directly by the browser using ES modules
- CSS is included via importmap as well

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

### Adding JavaScript Dependencies

To add a new JavaScript dependency:

```bash
# Add a package via importmap
docker compose exec app bin/console importmap:require package-name
```

This will update the `importmap.php` file with the new dependency.

## Templates

- Use the relics templates as the standard for new templates
- Follow the same structure and styling conventions as found in the relic templates
- Maintain consistency in UI components and layout across the application

## Database Management

- Use migrations for schema updates:
  ```bash
  # Generate a migration
  docker compose exec app bin/console make:migration

  # Run migrations
  docker compose exec app bin/console doctrine:migrations:migrate
  ```
- This approach is more robust and allows tracking of database changes over time
- It also makes it easier to deploy changes to production environments

## Data Import

The project includes a data import mechanism for saints information:

- Saints data is stored in `data/saints_info.yaml`
- The import is handled by a console command:
  ```bash
  docker compose exec app bin/console app:import-saints
  ```
- This command is automatically run during production deployment
- The data includes information about saints, their feast days, patronage, and other details
- New saints can be added by updating the YAML file and running the import command