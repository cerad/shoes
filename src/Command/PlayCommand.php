<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class PlayCommand extends Command
{
    protected static $defaultName = 'app:play';

    private RouterInterface $router;
    private RouteCollection $routeCollection;

    public function __construct(RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routes = $this->router->getRouteCollection();
        $route = $routes->get('index');
        $label = $route->getOption('label');

        //dump($route);
        dump($label);
        //dump($route->getDefaults());
        //dump($route->getOptions());

        return Command::SUCCESS;
    }
}
