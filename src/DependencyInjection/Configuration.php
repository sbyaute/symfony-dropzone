<?php
namespace Sbyaute\SymfonyDropzone\DependencyInjection;
use Sbyaute\SymfonyDropzone\Form\DropzoneType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
   public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('symfony-dropzone');
        return $treeBuilder;
    }
}
