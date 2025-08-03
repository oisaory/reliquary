<?php

namespace App\Service;

use App\Entity\AbstractImage;
use App\Entity\RelicImage;
use App\Entity\SaintImage;
use App\Entity\UserImage;
use App\Entity\Relic;
use App\Entity\Saint;
use App\Entity\User;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    private string $uploadDir;
    private SluggerInterface $slugger;
    private ImageManager $imageManager;

    public function __construct(string $uploadDir, SluggerInterface $slugger, ImageManager $imageManager)
    {
        $this->uploadDir = $uploadDir;
        $this->slugger = $slugger;
        $this->imageManager = $imageManager;
    }

    public function createRelicImage(UploadedFile $file, Relic $relic, User $uploader = null): RelicImage
    {
        $image = new RelicImage();
        $image->setOriginalFilename($file->getClientOriginalName());
        $image->setMimeType($file->getMimeType());
        $image->setSize($file->getSize());
        $image->setRelic($relic);

        // Set the uploader if provided, otherwise use the relic creator
        if ($uploader) {
            $image->setUploader($uploader);
        } else {
            $image->setUploader($relic->getCreator());
        }

        $fileData = $this->processUploadedFile($file);

        $image->setFilename($fileData['filename']);
        $image->setThumbnailFilename($fileData['thumbnailFilename']);

        return $image;
    }

    public function createUserImage(UploadedFile $file, User $user, User $uploader = null): UserImage
    {
        $image = new UserImage();
        $image->setOriginalFilename($file->getClientOriginalName());
        $image->setMimeType($file->getMimeType());
        $image->setSize($file->getSize());
        $image->setUser($user);

        // Set the uploader if provided, otherwise use the user themselves
        if ($uploader) {
            $image->setUploader($uploader);
        } else {
            $image->setUploader($user);
        }

        $fileData = $this->processUploadedFile($file);

        $image->setFilename($fileData['filename']);
        $image->setThumbnailFilename($fileData['thumbnailFilename']);

        return $image;
    }
    
    public function createSaintImage(UploadedFile $file, Saint $saint, User $uploader = null): SaintImage
    {
        $image = new SaintImage();
        $image->setOriginalFilename($file->getClientOriginalName());
        $image->setMimeType($file->getMimeType());
        $image->setSize($file->getSize());
        $image->setSaint($saint);

        // Set the uploader if provided
        if ($uploader) {
            $image->setUploader($uploader);
        }

        $fileData = $this->processUploadedFile($file);

        $image->setFilename($fileData['filename']);
        $image->setThumbnailFilename($fileData['thumbnailFilename']);

        return $image;
    }

    public function deleteImage(AbstractImage $image): void
    {
        // Delete original file
        $fullPath = $this->uploadDir . '/' . $image->getFilename();
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        
        // Delete thumbnail file if it exists
        if ($image->getThumbnailFilename()) {
            $fullThumbnailPath = $this->uploadDir . '/' . $image->getThumbnailFilename();
            if (file_exists($fullThumbnailPath)) {
                unlink($fullThumbnailPath);
            }
        }

        // Clean up empty directories
        $dir = dirname($fullPath);
        if (is_dir($dir) && count(scandir($dir)) <= 2) { // Only . and .. entries
            rmdir($dir);
        }
    }

    private function processUploadedFile(UploadedFile $file): array
    {
        $originalFilename = $file->getClientOriginalName();
        $safeFilename = $this->slugger->slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $thumbnailFilename = 'thumb_' . $newFilename;

        $subDir = $this->getUploadPath($file);
        $fullDir = $this->uploadDir . '/' . $subDir;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $file->move($fullDir, $newFilename);
        
        // Generate thumbnail
        $fullPath = $fullDir . '/' . $newFilename;
        $fullThumbnailPath = $fullDir . '/' . $thumbnailFilename;
        
        $this->imageManager->read($fullPath)
            ->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullThumbnailPath);

        return [
            'filename' => $subDir . '/' . $newFilename,
            'thumbnailFilename' => $subDir . '/' . $thumbnailFilename
        ];
    }

    private function getUploadPath(UploadedFile $file): string
    {
        $originalFilename = $file->getClientOriginalName();
        $hash = substr(md5($originalFilename . time()), 0, 2);
        $subDir = $hash[0] . '/' . $hash[1];

        return $subDir;
    }
}
