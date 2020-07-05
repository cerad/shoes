<?php declare(strict_types=1);

// config/routes.php
use App\Controller\ShoeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $shoePrefix = 'shoe';
    $routes->add('shoe_list', "/$shoePrefix/list")
        ->controller([ShoeController::class, 'list'])
    ;
    $routes->add('shoe_edit', "/$shoePrefix/edit/{shoeCode}")
        ->controller([ShoeController::class, 'edit'])
    ;
    $shoeStorePrefix = 'shoe-store';
    $routes->add('shoe-store_list', "/$shoeStorePrefix/list/{storeCode}")
        ->controller([ShoeController::class, 'list'])
        ->defaults(['storeCode' => 'ALL'])
    ;
};