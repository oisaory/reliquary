<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:make-user-admin',
    description: 'Grants ROLE_ADMIN to a user',
)]
class MakeUserAdminCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user to make admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $io->error(sprintf('User "%s" not found.', $username));
            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        
        // Check if user already has ROLE_ADMIN
        if (in_array('ROLE_ADMIN', $roles)) {
            $io->warning(sprintf('User "%s" already has ROLE_ADMIN.', $username));
            return Command::SUCCESS;
        }

        // Add ROLE_ADMIN to user roles
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles(array_unique($roles));
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('User "%s" now has ROLE_ADMIN.', $username));

        return Command::SUCCESS;
    }
}