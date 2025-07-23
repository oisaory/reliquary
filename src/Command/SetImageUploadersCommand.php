<?php

namespace App\Command;

use App\Entity\RelicImage;
use App\Entity\UserImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set-image-uploaders',
    description: 'Set uploaders for existing images',
)]
class SetImageUploadersCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Setting uploaders for existing images');

        // Set uploaders for relic images
        $relicImages = $this->entityManager->getRepository(RelicImage::class)->findAll();
        $relicImagesCount = count($relicImages);
        $relicImagesUpdated = 0;

        $io->progressStart($relicImagesCount);
        foreach ($relicImages as $image) {
            if ($image->getUploader() === null) {
                $relic = $image->getRelic();
                if ($relic && $relic->getCreator()) {
                    $image->setUploader($relic->getCreator());
                    $relicImagesUpdated++;
                }
            }
            $io->progressAdvance();
        }
        $io->progressFinish();

        // Set uploaders for user images
        $userImages = $this->entityManager->getRepository(UserImage::class)->findAll();
        $userImagesCount = count($userImages);
        $userImagesUpdated = 0;

        $io->progressStart($userImagesCount);
        foreach ($userImages as $image) {
            if ($image->getUploader() === null) {
                $user = $image->getUser();
                if ($user) {
                    $image->setUploader($user);
                    $userImagesUpdated++;
                }
            }
            $io->progressAdvance();
        }
        $io->progressFinish();

        $this->entityManager->flush();

        $io->success([
            'Uploaders set for existing images:',
            "- Relic images: $relicImagesUpdated/$relicImagesCount",
            "- User images: $userImagesUpdated/$userImagesCount",
        ]);

        return Command::SUCCESS;
    }
}