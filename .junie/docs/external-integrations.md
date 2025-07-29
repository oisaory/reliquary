# External Integrations

## OpenStreetMap Integration

The project uses OpenStreetMap's Nominatim API for geocoding:

- **OpenStreetMapService**: A service that provides methods for searching addresses
- Used for location autocomplete in relic forms
- Returns formatted results with coordinates and address details
- Respects OpenStreetMap's usage policy with proper User-Agent

## Image Management System

The project includes a comprehensive image management system:

- **ImageService**: A service that handles image uploads, storage, and deletion
- Supports different image types (RelicImage, UserImage)
- Uses a hashed directory structure to prevent filesystem issues with large numbers of files
- Handles file naming, moving, and cleanup
- Associates images with their uploaders for audit purposes