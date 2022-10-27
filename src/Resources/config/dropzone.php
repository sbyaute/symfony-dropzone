<?php
namespace Sbyaute\SymfonyDropzone\DependencyInjection\Configuration;

use Sbyaute\SymfonyDropzone\Form\DropzoneType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function(ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure() ;
    $services->set(DropzoneType::class)
        ->autowire();
};
