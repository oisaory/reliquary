# Reliquary

## Docker Setup

This project includes a Docker setup for local development. The setup includes:

- PHP 8.2 FPM
- Nginx web server
- PostgreSQL database
- Mailpit for email testing

### Requirements

- Docker
- Docker Compose

### Getting Started

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

### Services

- **Web Server**: http://localhost:8080
- **Database**: PostgreSQL (accessible via port 5432)
- **Mail Server**: Mailpit (accessible via http://localhost:8025)

### Common Commands

- Start the containers: `docker compose up -d`
- Stop the containers: `docker compose down`
- View logs: `docker compose logs -f`
- Access PHP container: `docker compose exec php bash`
- Run Symfony commands: `docker compose exec php bin/console <command>`

### Configuration

- PHP configuration can be modified in `docker/php/php.ini`
- Nginx configuration can be modified in `docker/nginx/default.conf`
- Database configuration can be modified in `.env` file or by setting environment variables

## Production Setup

The production environment uses Docker Compose with the following services:
- Apache with PHP
- PostgreSQL database
- Watchtower for automatic container updates

### Watchtower Configuration

Watchtower is configured to:
- Automatically update containers once a day at midnight
- Only update containers with the label `com.centurylinklabs.watchtower.enable=true`
- Expose an HTTP API for manual triggering of updates
- Clean up old images after updating

### Required Environment Variables

Add these to your production environment:
- `WATCHTOWER_HTTP_API_TOKEN`: Token for securing the Watchtower HTTP API

### GitHub Actions CI/CD

The CI/CD workflow automatically:
- Builds and pushes Docker images to GitHub Container Registry
- Triggers Watchtower to update containers on the production server

#### Required GitHub Secrets

Add these secrets to your GitHub repository:
- `WATCHTOWER_HTTP_API_TOKEN`: Same token as configured in your production environment
- `PRODUCTION_URL`: URL or IP address of your production server


### To do
* [x] Make the relic form look great
* [x] Fix create relic error
* [x] Make the relic view look great
* [x] Display the Saints listing
* [x] Add a home page and remove the home link
* [x] Import Saints
* [x] Create New Saint does not match Create New Relic standard
* [x] Add pagination
* [x] Make pagination look good
* [x] Is there a way to make pagination more configurable and less declarative?
* [x] Add geolocation to relics
* [x] Add an auto-completing address translated into geolocation
* [x] Add a map interface to see relics around the world
* [x] Ask for location data and if granted center the map on the person 
* [x] Configure CI/CD to ignore documentation changes
* [x] Integrate Watchtower for automated production updates
* [x] Add `.dockerignore` file for production image optimization
* [x] Implement semantic release configuration for automated versioning
* [x] Add asset map compilation step to production Dockerfile
* [x] Integrate dynamic versioning system across application
* [x] Add `app:create-user` command to create users via the CLI
* [x] Simplify environment configuration and update Docker Compose commands
* [ ] Add a map interface to add relic position
* [ ] Use turbo frames to increase responsiveness
* [x] Phone UI
  * [x] Burger menu
  * [x] Mobile-friendly relics display
* [x] Figure out a logo
* [ ] Add option to add a Saint image
* [ ] Only admin can access saint creation
* [x] Add user management panel for admins
* [ ] Implement a user profile
* [x] Create custom error pages (404, 403, 500)
* [ ] Workflow for relic approval
