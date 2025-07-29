# Maintenance

## Updating the Application

### Automatic Updates with Watchtower

The production setup includes Watchtower, which automatically updates containers to the latest available image:

- Watchtower checks for updates once a day at midnight
- Only containers with the label `com.centurylinklabs.watchtower.enable=true` are updated
- Watchtower exposes an HTTP API for manual triggering of updates

To manually trigger an update via the Watchtower API:
```bash
curl -H "Authorization: Bearer your-secure-token" -X POST http://your-server:8080/v1/update
```

### Manual Updates

If you prefer to update manually:

```bash
# Pull the latest images
docker compose pull

# Restart the containers
docker compose up -d
```

## Monitoring and Troubleshooting

```bash
# View logs for all services
docker compose logs

# View logs for a specific service
docker compose logs app
```