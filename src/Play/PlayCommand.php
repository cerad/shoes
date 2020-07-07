<?php

namespace App\Play;

use App\Entity\My;
use App\Shoe\Shoe;
use App\Shoe\ShoeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PlayCommand extends Command
{
    protected static $defaultName = 'app:play';

    private $entityManager;
    private $shoeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ShoeRepository $shoeRepository
    )
    {
        parent::__construct();
        $this->entityManager  = $entityManager;
        $this->shoeRepository = $shoeRepository;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shoe = new Shoe('xxx','name');
        $this->entityManager->persist($shoe);
        $this->entityManager->flush();

        $shoe = $this->shoeRepository->findOneByCode('xxx');
        echo "Shoe {$shoe->getId()} {$shoe->getName()}\n";

        return 0;
    }
}
