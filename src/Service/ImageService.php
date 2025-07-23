<?php

namespace App\Service;

use App\Entity\AbstractImage;
use App\Entity\RelicImage;
use App\Entity\UserImage;
use App\Entity\Relic;
use App\Entity\User;
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

        $filename = $this->processUploadedFile($file);

        $image->setFilename($filename);

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

        $filename = $this->processUploadedFile($file);

        $image->setFilename($filename);

        return $image;
    }

    public function deleteImage(AbstractImage $image): void
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

    private function processUploadedFile(UploadedFile $file): string
    {
        $originalFilename = $file->getClientOriginalName();
        $safeFilename = $this->slugger->slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $subDir = $this->getUploadPath($file);
        $fullDir = $this->uploadDir . '/' . $subDir;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        $file->move($fullDir, $newFilename);

        return $subDir . '/' . $newFilename;
    }

    private function getUploadPath(UploadedFile $file): string
    {
        $originalFilename = $file->getClientOriginalName();
        $hash = substr(md5($originalFilename . time()), 0, 2);
        $subDir = $hash[0] . '/' . $hash[1];

        return $subDir;
    }
}
