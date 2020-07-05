<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Shoe;
use App\Repository\ShoeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShoeController extends AbstractController
{
    private ShoeRepository $shoeRepository;

    public function __construct(ShoeRepository $shoeRepository)
    {
        $this->shoeRepository = $shoeRepository;
    }
    public function list()
    {
        $shoes = $this->shoeRepository->findAll();

        return $this->render('shoe/shoe_list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
    public function show(Shoe $shoe)
    {
        dump($shoe);

        $shoes = $this->shoeRepository->findAll();

        return $this->render('shoe/shoe_list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
    public function edit(string $shoeCode)
    {
        dump($shoeCode);

        //$shoes = $this->shoeRepository->findAll();
        $shoes = [];

        return $this->render('shoe/shoe_list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
}
