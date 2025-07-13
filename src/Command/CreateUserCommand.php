<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a user')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
            ->addOption('admin', 'a', InputOption::VALUE_NONE, 'Set the user as an admin')
            ->addOption('verified', 'c', InputOption::VALUE_NONE, 'Set the user as verified');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $isAdmin = $input->getOption('admin');
        $isVerified = $input->getOption('verified');

        // Check if user already exists
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($existingUser) {
            $io->error(sprintf('User with username "%s" already exists', $username));
            return Command::FAILURE;
        }

        // Check if email already exists
        $existingEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingEmail) {
            $io->error(sprintf('User with email "%s" already exists', $email));
            return Command::FAILURE;
        }

        // Create new user
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $user->setVerified($isVerified);

        // Set roles
        $roles = ['ROLE_USER'];
        $roles = ['ROLE_USER'];
        if ($isAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }
        $user->setRoles($roles);

        // Validate user
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            $io->error($errorMessages);
            return Command::FAILURE;
        }

        // Save user to database
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            
            $io->success(sprintf(
                'User created successfully: %s (ID: %d)%s%s',
                $username,
                $user->getId(),
                $isAdmin ? ' [ADMIN]' : '',
                $isVerified ? ' [VERIFIED]' : ''
            ));
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Error creating user: %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}