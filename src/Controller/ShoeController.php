<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Shoe;
use App\Repository\ShoeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShoeController extends AbstractController
{
    private ShoeRepository $shoeRepository;

    public function __construct(ShoeRepository $shoeRepository)
    {
        $this->shoeRepository = $shoeRepository;
    }

    /**
     * @Route("/shoe/list", name="shoe_list")
     */
    public function list()
    {
        $shoes = $this->shoeRepository->findAll();

        return $this->render('shoe/shoe_list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
    /**
     * @Route("/shoe/show/{id}", name="shoe_show")
     */
    public function show(Shoe $shoe)
    {
        dump($shoe);

        $shoes = $this->shoeRepository->findAll();

        return $this->render('shoe/shoe_list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
}
