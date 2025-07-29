# Build/Configuration Instructions

## Docker Setup

The project uses Docker for local development with the following services:

- PHP 8.2 with Apache
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
docker compose exec app composer install
```

4. Access the application in your browser:

```
http://localhost:8080
```

### Services

- **Web Server**: http://localhost:8080 (HTTP) and https://localhost:8443 (HTTPS)
- **Database**: PostgreSQL (accessible via port 5432)
- **Mail Server**: Mailpit (accessible via http://localhost:8025)

### Common Commands

- Start the containers: `docker compose up -d`
- Stop the containers: `docker compose down`
- View logs: `docker compose logs -f`
- Access PHP container: `docker compose exec app bash`
- Run Symfony commands: `docker compose exec app bin/console <command>`

### Configuration

- PHP configuration can be modified in `docker/app/php.ini`
- Apache configuration can be modified in `docker/app/apache-dev.conf` and `docker/app/apache-ssl.conf`
- Database configuration can be modified in `.env` file or by setting environment variables

## Environment Configuration

The project uses Symfony's environment system:

- `.env` contains default values for environment variables
- `.env.local` (not committed) should be used for local overrides
- `.env.$APP_ENV` for environment-specific defaults
- `.env.$APP_ENV.local` for environment-specific overrides