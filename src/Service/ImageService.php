<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\ImageOwnerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    private string $uploadDir;
    private SluggerInterface $slugger;

    public function __construct(string $uploadDir, SluggerInterface $slugger)
    {
        $this->uploadDir = $uploadDir;
        $this->slugger = $slugger;
    }

    public function createFromUploadedFile(UploadedFile $file, ImageOwnerInterface $owner, string $ownerType): Image
    {
        $originalFilename = $file->getClientOriginalName();
        $safeFilename = $this->slugger->slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $subDir = $this->getUploadPath($file);
        $fullDir = $this->uploadDir . '/' . $subDir;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $size = $file->getSize();
        $mimeType = $file->getMimeType();
        $file->move($fullDir, $newFilename);

        $image = new Image();
        $image->setFilename($subDir . '/' . $newFilename);
        $image->setOriginalFilename($originalFilename);
        $image->setMimeType($mimeType);
        $image->setSize($size);
        $image->setOwner($owner);
        $image->setOwnerType($ownerType);

        return $image;
    }

    public function deleteImage(Image $image): void
    {
        $fullPath = $this->uploadDir . '/' . $image->getFilename();

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Clean up empty directories
        $dir = dirname($fullPath);
        if (is_dir($dir) && count(scandir($dir)) <= 2) { // Only . and .. entries
            rmdir($dir);
        }
    }

    private function getUploadPath(UploadedFile $file): string
    {
        $originalFilename = $file->getClientOriginalName();
        $hash = substr(md5($originalFilename . time()), 0, 2);
        $subDir = $hash[0] . '/' . $hash[1];

        return $subDir;
    }
}
