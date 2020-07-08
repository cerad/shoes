<?php declare(strict_types=1);

namespace App\Shoe\Edit;

use Cerad\Common\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Response;

class ShoeEditAction implements ActionInterface
{
    public function __invoke() : Response
    {
        return new Response('Shoe Edit');
    }
}