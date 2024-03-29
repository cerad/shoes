<?php declare(strict_types=1);

namespace App\Base\Index;

use Cerad\Common\Action\ActionInterface;
use Cerad\Common\Action\RenderTwigTrait;
use Symfony\Component\HttpFoundation\Response;

class BaseIndexAction implements ActionInterface
{
    use RenderTwigTrait;

    public function __invoke() : Response
    {
        return $this->render('@Base/Index/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}