<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Shoe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShoeInitCommand extends Command
{
    protected static $defaultName = 'app:init-shoes';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;
        $conn = $em->getConnection();
        $conn->executeUpdate('DELETE FROM shoes WHERE id > 0');

        $shoe1 = new Shoe('AT6175 003','W AIR MAX 200','BLACK/BLACK');
        $shoe2 = new Shoe('CT1185 900','W AIR MAX 200','MTLC RED BRONZE');
        $shoe3 = new Shoe('AO3166 300','W NIKE PRE-LOVE O.X.','TWILIGHT MARSH/SUMMIT WHITE');

        $em->persist($shoe1);
        $em->persist($shoe2);
        $em->persist($shoe3);
        $em->flush();

        return Command::SUCCESS;
    }
}