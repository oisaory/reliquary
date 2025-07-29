# Production Configuration

The project supports two deployment options: Docker-based deployment and Platform.sh deployment.

## Docker Production Setup

The project uses Docker for production deployment with the following services:

- App service (PHP 8.2 with Apache)
- PostgreSQL database
- Watchtower for automatic updates

The production Docker image uses a multi-stage build process:

1. **Build Stage**:
   - Installs all dependencies including dev dependencies
   - Optimizes Composer autoloader
   - Prepares the application

2. **Production Stage**:
   - Uses a clean PHP 8.2 Apache base
   - Copies only necessary files from the build stage
   - Configures OPcache for optimal performance
   - Includes SSL/TLS support
   - Runs database migrations automatically on startup

The production image includes several optimizations:
- OPcache configuration for better performance
- Precompiled assets
- Warmed up Symfony cache
- Reduced image size by excluding development dependencies

## Platform.sh Deployment

The project is also configured for deployment on Platform.sh:

- Configuration is defined in `.platform.app.yaml` and `.platform` directory
- Uses PHP 8.3 runtime with specific extensions
- Includes build and deploy hooks for Symfony Cloud
- Configured with security check cron job

To deploy to Platform.sh:

1. Create a Platform.sh project
2. Add the Platform.sh remote to your Git repository
3. Push to the Platform.sh remote:

```bash
git push platform main
```

The Platform.sh configuration includes:

- Automatic HTTPS with Let's Encrypt
- Preloaded OPcache for better performance
- Persistent storage for var directory
- Symfony Cloud integration

### Production Environment Variables

Create a `.env` file with the following variables:

```
DOCKER_REGISTRY=ghcr.io/your-github-username
IMAGE_TAG=latest
APP_SECRET=your-app-secret
POSTGRES_DB=reliquary
POSTGRES_USER=app
POSTGRES_PASSWORD=your-secure-password
APACHE_SSL_PORT=443
MAILER_DSN=smtp://user:pass@smtp.example.com:25
WATCHTOWER_HTTP_API_TOKEN=your-secure-token
```

### Deployment Options

#### Option 1: Automatic Deployment with CI/CD

The project includes a GitHub Actions workflow that automatically builds and pushes Docker images to GitHub Container Registry (GHCR) when changes are pushed to the main branch.

1. **GitHub Secrets Configuration**

   The workflow uses the following GitHub secrets:
   - `GITHUB_TOKEN`: Automatically created and provided by GitHub for GitHub Actions workflows
   - `WATCHTOWER_HTTP_API_TOKEN`: Token for authenticating with the Watchtower HTTP API
   - `PRODUCTION_URL`: URL or IP address of your production server where Watchtower is running

2. **Push Changes to Main Branch**

   When you push changes to the main branch, the GitHub Actions workflow will automatically:
   - Build the App Docker image
   - Push it to GitHub Container Registry with appropriate tags
   - Trigger Watchtower on your production server to update containers with the latest images

3. **Deploy on Your Server**

   On your production server:
   ```bash
   # Get the production compose file from the repository
   curl -O https://raw.githubusercontent.com/cesarscur/reliquary/main/compose.prod.yaml
   mv compose.prod.yaml compose.yaml

   # Create a .env file with your production settings
   # Pull the latest images and start the containers
   docker compose pull
   docker compose up -d
   ```

#### Option 2: Manual Deployment

If you prefer to build and deploy manually:

1. **Build the Images Locally**
   ```bash
   docker build -t ghcr.io/your-github-username/reliquary:latest -f docker/app/Dockerfile.prod .
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

## SSL/TLS Configuration

The App container is configured to serve HTTPS with SSL/TLS support. For production:

1. **Replace Self-Signed Certificates**
   - Mount your SSL certificates into the container:
     ```yaml
     volumes:
       - /etc/letsencrypt/live/your-domain.com/fullchain.pem:/etc/apache2/ssl/apache.crt
       - /etc/letsencrypt/live/your-domain.com/privkey.pem:/etc/apache2/ssl/apache.key
     ```

2. **Let's Encrypt Integration**
   - The production setup in compose.prod.yaml includes volume mounts for Let's Encrypt certificates