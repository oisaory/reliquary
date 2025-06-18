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
