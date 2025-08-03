# Image Thumbnails in Reliquary

This document describes how image thumbnails are implemented in the Reliquary project and provides instructions for generating thumbnails for existing images.

## Overview

The Reliquary project now supports automatic thumbnail generation for all uploaded images. Thumbnails are generated at upload time and are stored alongside the original images. The thumbnails are used in various places throughout the application to improve performance and reduce bandwidth usage.

## Implementation Details

- Thumbnails are generated using the [Intervention Image](https://image.intervention.io/) library
- Thumbnails are 200x200 pixels, maintaining aspect ratio
- Thumbnails are stored in the same directory as the original images, with a `thumb_` prefix
- The `AbstractImage` entity has a `thumbnailFilename` field to store the path to the thumbnail

## Templates

The following templates have been updated to use thumbnails where appropriate:

- `relic/_form.html.twig` - Uses thumbnails for image previews in forms
- `relic/approve.html.twig` - Uses thumbnails for additional images and user profile images
- `relic/show.html.twig` - Uses thumbnails for additional images and user profile images
- `saint/show.html.twig` - Uses thumbnails for additional images

## Generating Thumbnails for Existing Images

A command has been created to generate thumbnails for existing images. To run this command, you need to have either the GD or Imagick PHP extension installed.

### Prerequisites

1. Install either the GD or Imagick PHP extension:

   For GD:
   ```bash
   apt-get update && apt-get install -y libgd-dev libjpeg-dev
   docker-php-ext-configure gd --with-jpeg
   docker-php-ext-install gd
   ```

   For Imagick:
   ```bash
   apt-get update && apt-get install -y libmagickwand-dev
   pecl install imagick
   docker-php-ext-enable imagick
   ```

2. Configure the Intervention Image service to use the installed driver:

   In `config/services.yaml`:
   ```yaml
   Intervention\Image\ImageManager:
       factory: ['Intervention\Image\ImageManager', 'gd']  # or 'imagick'
   ```

### Running the Command

Once the prerequisites are met, you can run the command to generate thumbnails for all existing images:

```bash
php bin/console app:generate-thumbnails
```

This command will:
1. Process all image types (RelicImage, SaintImage, UserImage)
2. Skip images that already have thumbnails
3. Generate thumbnails for images that don't have them
4. Update the database with the thumbnail filenames
5. Provide progress feedback and statistics

## Troubleshooting

If you encounter issues with thumbnail generation, check the following:

1. Make sure either the GD or Imagick PHP extension is installed
2. If using GD, ensure it's configured with JPEG support (requires libjpeg-dev and proper configuration)
3. Verify that the configured driver in `services.yaml` matches the installed extension
4. Ensure the upload directory is writable by the web server
5. Check that the original images exist in the expected locations

### Common Errors

- **"Attempted to call function "imagecreatefromjpeg" from namespace "Intervention\Image\Drivers\Gd\Decoders""**: This error indicates that the GD extension is installed but lacks JPEG support. Make sure you've installed libjpeg-dev and configured GD with JPEG support as shown in the Prerequisites section.

## Future Improvements

Potential future improvements to the thumbnail system:

1. Support for multiple thumbnail sizes (small, medium, large)
2. Lazy loading of thumbnails for better performance
3. Integration with a CDN for serving images
4. Image optimization to further reduce file sizes