<?php

namespace Doctrine\Bundle\OXMBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Bundle\DoctrineAbstractBundle\DependencyInjection\AbstractDoctrineExtension;

/**
 * Doctrine OXM extension.
 *
 * @author Igor Golovanov <igor.golovanov@gmail.com>
 */
class DoctrineMongoDBExtension extends AbstractDoctrineExtension
{
    /**
     * Responds to the doctrine_mongodb configuration parameter.
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load DoctrineMongoDBBundle/Resources/config/mongodb.xml
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('oxm.xml');
        
        

        
        // set some options as parameters and unset them
        $config = $this->overrideParameters($config, $container);
    }
    
    
    /**
     * Uses some of the extension options to override DI extension parameters.
     *
     * @param array $options The available configuration options
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function overrideParameters($options, ContainerBuilder $container)
    {
        $overrides = array(
            'proxy_namespace',
            'proxy_dir',
            'auto_generate_proxy_classes',
        );

        foreach ($overrides as $key) {
            if (isset($options[$key])) {
                $container->setParameter('doctrine.oxm.'.$key, $options[$key]);

                // the option should not be used, the parameter should be referenced
                unset($options[$key]);
            }
        }

        return $options;
    }


    protected function getObjectManagerElementName($name)
    {
        return 'doctrine.oxm.' . $name;
    }

    protected function getMappingObjectDefaultName()
    {
        return 'XmlEntity';
    }

    protected function getMappingResourceConfigDirectory()
    {
        return 'Resources/config/doctrine';
    }

    protected function getMappingResourceExtension()
    {
        return 'oxm';
    }

    public function getAlias()
    {
        return 'doctrine_oxm';
    }

    /**
     * @return string
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
