<?php

namespace App\Command;
use App\Entity\User ;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserAddrolesCommand extends Command
{
    protected static $defaultName = 'app:user:addroles';
    private $em ;
    protected static $defaultDescription = 'Add a short description for your command';
    public function __construct(EntityManagerInterface $em){
      $this->em =$em ;
       parent::__construct();
}
    protected function configure(): void
    {
        $this
            ->setDescription('modifieer')
            ->addArgument('email', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('roles', InputArgument::REQUIRED, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $roles = $input->getArgument('roles');
        $userRepository = $this->em->getRepository(User::class);
        $user=$userRepository->findOneByEmail($email);
        if ($user) {
            $user->addRoles($roles);
            $io->success('succes role added  to user');
        }else{
            $io->error('the is no user with this mail');
        }

        return 0;
    }
}
