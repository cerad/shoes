<?php declare(strict_types=1);

// config/routes.php
use App\Controller\ShoeController;
use App\Shoe\Table\ShoeListAction;
use App\ShoeStore\Table\ShoeStoreListAction;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $shoePrefix = 'shoe';
    $routes->add('shoe_list', "/$shoePrefix/list")
        ->controller(ShoeListAction::class)
    ;
    $routes->add('shoe_edit', "/$shoePrefix/edit/{shoeCode}")
        ->controller([ShoeController::class, 'edit'])
    ;
    $shoeStorePrefix = 'shoe-store';
    $routes->add('shoe-store_list', "/$shoeStorePrefix/list/{storeCode}")
        ->controller(ShoeStoreListAction::class)
        ->defaults(['storeCode' => 'ALL'])
    ;
};