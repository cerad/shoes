<?php declare(strict_types=1);

// config/routes.php
use App\Shoe\Edit\ShoeEditAction;
use App\Shoe\Table\ShoeListAction;
use App\ShoeStore\Table\ShoeStoreListAction;

use App\Base\Index\BaseIndexAction;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->add('base_index', "/")
        ->controller(BaseIndexAction::class)
    ;
    $shoePrefix = 'shoe';
    $routes->add('shoe_list', "/$shoePrefix/list")
        ->controller(ShoeListAction::class)
    ;
    $routes->add('shoe_edit', "/$shoePrefix/edit/{shoeCode}")
        ->controller([ShoeEditAction::class])
    ;
    $shoeStorePrefix = 'shoe-store';
    $routes->add('shoe-store_list', "/$shoeStorePrefix/list/{storeCode}")
        ->controller(ShoeStoreListAction::class)
        ->defaults(['storeCode' => 'ALL'])
    ;
};