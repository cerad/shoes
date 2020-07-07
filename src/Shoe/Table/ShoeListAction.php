<?php declare(strict_types=1);

namespace App\Shoe\Table;

use App\Shoe\ShoeRepository;

use Cerad\Common\Action\ActionInterface;
use Cerad\Common\Action\RenderTrait;

use Symfony\Component\HttpFoundation\Response;

final class ShoeListAction implements ActionInterface
{
    use RenderTrait;

    public function __invoke(ShoeRepository $shoeRepository) : Response
    {
        $shoes = $shoeRepository->findAll();

        return $this->render('@Shoe/Table/list.html.twig', [
            'shoes' => $shoes,
        ]);
    }
}