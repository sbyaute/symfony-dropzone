<?php
namespace Sbyaute\SymfonyDropzone\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
class SymfonyDropzoneExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('dropzone.php');
        $configuration = $this->getConfiguration($configs, $container);
       $this->processConfiguration($configuration, $configs);
     $this->loadTwigTheme($container);
    }

    private function loadTwigTheme(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }
        $container->setParameter('twig.form.resources', array_merge(
            [
                '@SymfonyDropzone/Form/dropzone.html.twig'
            ],
            $container->getParameter('twig.form.resources')
        ));
    }
}
