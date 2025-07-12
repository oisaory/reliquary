# Reliquary Project Deployment Guide

This document provides instructions for deploying the Reliquary project to a production environment using Docker.

## Prerequisites

- A server with Docker and Docker Compose installed
- Access to a Docker registry (Docker Hub, GitHub Container Registry, etc.)
- GitHub token with appropriate permissions (if using the provided CI/CD pipeline)

## Deployment Options

### Option 1: Automatic Deployment with CI/CD

The project includes a GitHub Actions workflow that automatically builds and pushes Docker images to GitHub Container Registry (GHCR) when changes are pushed to the main branch.

1. **GitHub Token Configuration**

   The workflow uses the `GITHUB_TOKEN` secret that GitHub automatically creates and provides for GitHub Actions workflows. No additional secrets need to be configured for the GitHub Container Registry authentication.

   The workflow includes the necessary permissions for pushing to the GitHub Container Registry:
   ```yaml
   permissions:
     contents: read
     packages: write
   ```

2. **Push Changes to Main Branch**

   When you push changes to the main branch, the GitHub Actions workflow will automatically:
   - Build the Apache+PHP Docker image
   - Push it to GitHub Container Registry with appropriate tags

3. **Deploy on Your Server**

   On your production server:

   ```bash
   # Get the production compose file from the repository
   curl -O https://raw.githubusercontent.com/cesarscur/reliquary/main/compose.prod.yaml
   mv compose.prod.yaml compose.yaml
   ```
   ```bash
   # Create a .env.prod file with your production settings
   cat > .env.prod << EOL
   DOCKER_REGISTRY=ghcr.io/your-github-username
   IMAGE_TAG=latest
   APP_SECRET=your-app-secret
   POSTGRES_DB=reliquary
   POSTGRES_USER=app
   POSTGRES_PASSWORD=your-secure-password
   APACHE_SSL_PORT=443
   EOL

   # Pull the latest images and start the containers
   docker compose --env-file .env.prod pull
   docker compose --env-file .env.prod up -d
   ```

### Option 2: Manual Deployment

If you prefer to build and deploy manually:

1. **Build the Images Locally**

   ```bash
   docker build -t ghcr.io/your-github-username/reliquary:latest -f docker/apache/Dockerfile.prod .
   ```

2. **Push to Your Registry**

   ```bash
   # Login to GitHub Container Registry
   echo $GITHUB_TOKEN | docker login ghcr.io -u your-github-username --password-stdin

   # Push the image
   docker push ghcr.io/your-github-username/reliquary:latest
   ```

3. **Deploy on Your Server**

   Follow the same steps as in Option 1, step 3.

## Production Configuration

### Environment Variables

Create a `.env.prod` file with the following variables:

```
DOCKER_REGISTRY=ghcr.io/your-github-username
IMAGE_TAG=latest
APP_SECRET=your-app-secret
POSTGRES_DB=reliquary
POSTGRES_USER=app
POSTGRES_PASSWORD=your-secure-password
APACHE_SSL_PORT=443
```

### Database Management

For production, consider:

1. **Using a Managed Database Service**

   Instead of running PostgreSQL in a container, consider using a managed database service like AWS RDS, Google Cloud SQL, or DigitalOcean Managed Databases.

2. **Regular Backups**

   Set up regular database backups:

   ```bash
   # Example backup script
   docker compose exec database pg_dump -U app reliquary > backup_$(date +%Y%m%d).sql
   ```

### SSL/TLS Configuration

The Apache container in this project is configured to serve HTTPS on port 443 with SSL/TLS support. The container generates a self-signed SSL certificate during build time, which is suitable for development and testing.

For production use:

1. **Replace Self-Signed Certificates**

   For production environments, you should replace the self-signed certificates with proper certificates from a trusted Certificate Authority (CA):

   - Mount your SSL certificates into the container:
     ```yaml
     volumes:
       - ./ssl/your-cert.crt:/etc/apache2/ssl/apache.crt
       - ./ssl/your-key.key:/etc/apache2/ssl/apache.key
     ```

2. **Let's Encrypt Integration**

   For automatic certificate management, consider using Let's Encrypt:

   - Set up a volume for Let's Encrypt certificates
   - Configure a renewal process (e.g., using certbot in a separate container)
   - Mount the certificates into the Apache container

## Maintenance

### Updating the Application

To update the application:

```bash
# Pull the latest images
docker compose --env-file .env.prod pull

# Restart the containers
docker compose --env-file .env.prod up -d
```

### Monitoring

Consider adding monitoring tools:

- Prometheus for metrics collection
- Grafana for visualization
- Loki for log aggregation

### Scaling

For higher traffic loads:

1. **Horizontal Scaling**

   Deploy multiple instances of the Apache container behind a load balancer.

2. **Vertical Scaling**

   Increase the resources (CPU, memory) allocated to your containers.

## Troubleshooting

### Checking Logs

```bash
# View logs for all services
docker compose logs

# View logs for a specific service
docker compose logs apache
```

### Common Issues

1. **Database Connection Issues**

   Check that the `DATABASE_URL` environment variable is correctly set and that the database is accessible.

2. **Permission Issues**

   Ensure that the volume mounts have the correct permissions.

3. **Image Pull Failures**

   Verify that your Docker registry credentials are correct and that the images exist in the registry.
