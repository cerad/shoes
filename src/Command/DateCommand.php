<?php

namespace App\Command;

use App\Entity\My;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DateCommand extends Command
{
    protected static $defaultName = 'app:date';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $my = new My();
        $my->setName('Name');
        $my->setCreatedOn(new \DateTime());
        $this->entityManager->persist($my);
        $this->entityManager->flush();

        return 0;
    }
}
