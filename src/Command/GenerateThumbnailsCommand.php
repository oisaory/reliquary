<?php

namespace App\Command;

use App\Entity\AbstractImage;
use App\Entity\RelicImage;
use App\Entity\SaintImage;
use App\Entity\UserImage;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
    private ImageService $imageService;

    public function __construct(
        EntityManagerInterface $entityManager,
        string $uploadDir,
        ImageService $imageService
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->uploadDir = $uploadDir;
        $this->imageService = $imageService;
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force regeneration of all thumbnails, even if they already exist'
            )
            ->setHelp('This command generates thumbnails for all existing images that do not have thumbnails yet. Use the --force option to regenerate all thumbnails.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');
        
        if ($force) {
            $io->title('Force regenerating thumbnails for all existing images');
        } else {
            $io->title('Generating thumbnails for existing images without thumbnails');
        }

        // Process RelicImages
        $this->processThumbnails(RelicImage::class, $io, $force);

        // Process SaintImages
        $this->processThumbnails(SaintImage::class, $io, $force);

        // Process UserImages
        $this->processThumbnails(UserImage::class, $io, $force);

        $io->success('All thumbnails have been generated successfully.');

        return Command::SUCCESS;
    }

    private function processThumbnails(string $entityClass, SymfonyStyle $io, bool $force = false): void
    {
        $io->section(sprintf('Processing %s', $entityClass));

        $repository = $this->entityManager->getRepository($entityClass);
        $images = $repository->findAll();

        $totalImages = count($images);
        $processedImages = 0;
        $skippedImages = 0;
        $errorImages = 0;
        $regeneratedImages = 0;

        $io->progressStart($totalImages);

        foreach ($images as $image) {
            $io->progressAdvance();

            if ($image->getThumbnailFilename() !== null && !$force) {
                $skippedImages++;
                continue;
            }

            try {
                // If force is true and thumbnail already exists, we're regenerating
                $isRegeneration = $force && $image->getThumbnailFilename() !== null;
                
                $this->generateThumbnail($image);
                
                if ($isRegeneration) {
                    $regeneratedImages++;
                } else {
                    $processedImages++;
                }
            } catch (\Exception $e) {
                $errorImages++;
                $io->error(sprintf('Error processing image %s: %s', $image->getFilename(), $e->getMessage()));
            }
        }

        $io->progressFinish();
        
        if ($force) {
            $io->table(
                ['Total', 'Processed', 'Regenerated', 'Skipped', 'Errors'],
                [[$totalImages, $processedImages, $regeneratedImages, $skippedImages, $errorImages]]
            );
        } else {
            $io->table(
                ['Total', 'Processed', 'Skipped', 'Errors'],
                [[$totalImages, $processedImages, $skippedImages, $errorImages]]
            );
        }
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
        
        // Generate thumbnail using the ImageService
        $this->imageService->generateThumbnail($originalPath, $thumbnailPath);
        
        // Update the entity
        $thumbnailRelativePath = dirname($image->getFilename()) . '/' . $thumbnailFilename;
        $image->setThumbnailFilename($thumbnailRelativePath);
        
        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }
}