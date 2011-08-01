# Doctrine 2 OXM Bundle

Doctrine OXM is a PHP 5.3 project for PHP object to XML mapping that provides support for persisting the XML to a file system via common Doctrine techniques.  


## Instaliation

To use the OXM, you'll need OXM library provided by Doctrine and one bundle that integrates them into Symfony. 
If you're using the Symfony Standard Distribution, add the following to the deps file at the root of your project:

    [doctrine-oxm]
        git=http://github.com/doctrine/oxm.git
        target=/doctrine-oxm

    [DoctrineOXMBundle]
        git=http://github.com/doctrine/DoctrineOXMBundle.git
        target=/bundles/Doctrine/Bundle/OXMBundle

Now, update the vendor libraries by running:

    $ php bin/vendors install

Next, add the Doctrine\OXM and Doctrine\Bundle\OXMBundle namespaces to the app/autoload.php file so that these libraries can be autoloaded. 
Be sure to add them anywhere above the Doctrine namespace (shown here):

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Doctrine\\OXM'             => __DIR__.'/../vendor/doctrine-oxm/lib',
        'Doctrine\\Bundle'          => __DIR__.'/../vendor/bundles',
        // ...
    ));

Register file with OXM annotations:

    // app/autoload.php
    AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine-oxm/lib/Doctrine/OXM/Mapping/Driver/DoctrineAnnotations.php');

Finally, enable the new bundle in the kernel:

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Doctrine\Bundle\OXMBundle\DoctrineOXMBundle(),
        );

        // ...
    }

Congratulations! You're ready to get to work.

## Configuration

To get started, you'll need some basic configuration that sets up the document manager. 
The easiest way is to enable auto_mapping, which will activate the OXM across your application:


    # app/config/config.yml
    doctrine_oxm:
        storages:
            default:
                path: "%kernel.root_dir%/doctrine-oxm-storage"

        xml_entity_managers:
            default:
                auto_mapping: true

Full config:

    doctrine_oxm:
        proxy_namespace:    Test_Proxies
        auto_generate_proxy_classes:    true
        proxy_dir: "%kernel.cache_dir%/doctrine/oxm/Proxies"


        default_xml_entity_manager:  xem1
        default_storage:             storg1

        storages:
            storg1:
                path: "%kernel.root_dir%/doctrine-oxm-storage/storg1"
            storg2:
                path: "%kernel.root_dir%/doctrine-oxm-storage/storg2"

        xml_entity_managers:
            xem1:
                auto_mapping: false
                mappings:
                    FooBundle:   annotations
                metadata_cache_driver: apc
            xem2:
                auto_mapping: false
                storage:         storg2
                mappings:
                    BarBundle:
                        type:   yml
                        dir:    %kernel.cache_dir%
                        prefix: prefix_val
                        alias:  alias_val
                        is_bundle: false
                metadata_cache_driver: apc