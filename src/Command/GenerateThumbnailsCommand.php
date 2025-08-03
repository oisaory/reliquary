<?php

namespace App\Command;

use App\Entity\AbstractImage;
use App\Entity\RelicImage;
use App\Entity\SaintImage;
use App\Entity\UserImage;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-thumbnails',
    description: 'Generate thumbnails for existing images',
)]
class GenerateThumbnailsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private string $uploadDir;
    private ImageManager $imageManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        string $uploadDir,
        ImageManager $imageManager
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->uploadDir = $uploadDir;
        $this->imageManager = $imageManager;
    }

    protected function configure(): void
    {
        $this->setHelp('This command generates thumbnails for all existing images that do not have thumbnails yet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating thumbnails for existing images');

        // Process RelicImages
        $this->processThumbnails(RelicImage::class, $io);

        // Process SaintImages
        $this->processThumbnails(SaintImage::class, $io);

        // Process UserImages
        $this->processThumbnails(UserImage::class, $io);

        $io->success('All thumbnails have been generated successfully.');

        return Command::SUCCESS;
    }

    private function processThumbnails(string $entityClass, SymfonyStyle $io): void
    {
        $io->section(sprintf('Processing %s', $entityClass));

        $repository = $this->entityManager->getRepository($entityClass);
        $images = $repository->findAll();

        $totalImages = count($images);
        $processedImages = 0;
        $skippedImages = 0;
        $errorImages = 0;

        $io->progressStart($totalImages);

        foreach ($images as $image) {
            $io->progressAdvance();

            if ($image->getThumbnailFilename() !== null) {
                $skippedImages++;
                continue;
            }

            try {
                $this->generateThumbnail($image);
                $processedImages++;
            } catch (\Exception $e) {
                $errorImages++;
                $io->error(sprintf('Error processing image %s: %s', $image->getFilename(), $e->getMessage()));
            }
        }

        $io->progressFinish();
        $io->table(
            ['Total', 'Processed', 'Skipped', 'Errors'],
            [[$totalImages, $processedImages, $skippedImages, $errorImages]]
        );
    }

    private function generateThumbnail(AbstractImage $image): void
    {
        $originalPath = $this->uploadDir . '/' . $image->getFilename();
        
        if (!file_exists($originalPath)) {
            throw new \Exception(sprintf('Original image file not found: %s', $originalPath));
        }

        $pathInfo = pathinfo($originalPath);
        $thumbnailFilename = 'thumb_' . basename($image->getFilename());
        $thumbnailPath = $pathInfo['dirname'] . '/' . $thumbnailFilename;
        
        // Generate thumbnail
        $this->imageManager->read($originalPath)
            ->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($thumbnailPath);
        
        // Update the entity
        $thumbnailRelativePath = dirname($image->getFilename()) . '/' . $thumbnailFilename;
        $image->setThumbnailFilename($thumbnailRelativePath);
        
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }
}