<?php declare(strict_types=1);

// config/services.php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Cerad\Common\Action\ActionInterface;

return function(ContainerConfigurator $configurator)
{
    $services = $configurator->services()->defaults()
        ->autowire()
        ->autoconfigure()
    ;
    $services->instanceof(ActionInterface::class)
        ->tag('controller.service_arguments');

    //$services->load('App\\', '../src/*')
    //    ->exclude('../src/{DependencyInjection,Migrations,Tests,Kernel.php}');

    // Base
    $services->load('App\\Base\\', '../src/Base/*')
        ->exclude('../src/Base/{}');

    // Shoe
    $services->load('App\\Shoe\\', '../src/Shoe/*')
        ->exclude('../src/Shoe/{Shoe.php,ShoeMapper.php}');

    // ShoeStore
    $files = implode(',',[
        'ShoeStore.php',
        'ShoeStoreMapper.php',
        'ShoeStoreSummaryRow.php',
        'Table/MyFormView.php',
    ]);

    $services->load('App\\ShoeStore\\', '../src/ShoeStore/*')
        ->exclude("../src/ShoeStore/{{$files}}");
};