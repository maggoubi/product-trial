<?php

namespace App\Command;

use App\Dto\UserDto;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an user account',
)]
class CreateAdminCommand extends Command
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        parent::__construct(); 
        $this->userService = $userService; 
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $questionEmail = new Question('Email : ');
        $email = $helper->ask($input, $output, $questionEmail);

        $questionPassword = new Question('Password : ');
        $questionPassword->setHidden(true);
        $password = $helper->ask($input, $output, $questionPassword);
		
		$questionFirstName = new Question('First Name : ');
        $firstname = $helper->ask($input, $output, $questionFirstName);
				
        $userDto = new UserDto();
        $userDto->setEmail($email);
		$userDto->setFirstname($firstname);
        $userDto->setPassword($password);

        $user = $this->userService->createUser($userDto);

        $io->success("User created successfully : $email");

        return Command::SUCCESS;
    }
}
