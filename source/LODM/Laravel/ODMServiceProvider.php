<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\LODM\Laravel;

use Illuminate\Support\ServiceProvider;
use Interop\Container\ContainerInterface;
use Spiral\Core\ConfiguratorInterface;
use Spiral\Core\Container;
use Spiral\Core\FactoryInterface;
use Spiral\Core\HippocampusInterface;
use Spiral\Core\ResolverInterface;
use Spiral\Files\FileManager;
use Spiral\Files\FilesInterface;
use Spiral\LODM\Support\Memory;
use Spiral\LODM\Support\SharedContainer;
use Spiral\ODM\ODM;
use Spiral\Tokenizer\ClassLocator;
use Spiral\Tokenizer\ClassLocatorInterface;
use Spiral\Tokenizer\Tokenizer;
use Spiral\Tokenizer\TokenizerInterface;
use Spiral\Validation\ValidatorInterface;

/**
 * Mounts laravel bindings and initiates spiral container.
 */
class ODMServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider to initiate global container.
     */
    public function register()
    {
        /**
         * Some spiral functions require global/static container, for example it provides you ability to
         * write code like:
         * new Post();
         *
         * In other scenario you with either be required to use document function of ODM component or
         * provide odm instance into your document explicitly:
         *
         * new Post([], null, $odm);
         *
         * @var Container $container
         */
        SharedContainer::initContainer($container = new Container());

        //Container bindings
        $container->bind(FactoryInterface::class, $container);
        $container->bind(ResolverInterface::class, $container);
        $container->bind(ContainerInterface::class, $container);

        //Since laravel uses this method for bindings, we can use it too
        $container->bind(TokenizerInterface::class, Tokenizer::class);
        $container->bind(ClassLocatorInterface::class, ClassLocator::class);

        //Spiral has it's own validation mechanism which is represented by a simple interface
        //we can wrap laravel validation functionality and rules
        $container->bind(ValidatorInterface::class, LaravelValidator::class);

        //Required for tokenizer to read file
        $container->bind(FilesInterface::class, FileManager::class);

        //Laravel also uses it's own configuration source, let's define our wrapper in spiral
        //container, default settings will use folder "spiral" under config directory to prevent
        //collisions
        $container->bindSingleton(ConfiguratorInterface::class, LaravelConfigurator::class);

        //ODM and some other components also use so called application memory (see doc) to store
        //behaviour schemas, we can use simple wrapper
        $container->bindSingleton(HippocampusInterface::class, $container->make(Memory::class, [
            'directory' => storage_path('/')
        ]));

        //Ok, now can define our ODM as singleton
        $this->app->singleton(ODM::class, function () use ($container) {
            //Container will do the rest, since ODM stated as singleton we
            //will always get same instance
            return $container->get(ODM::class);
        });
    }

    /**
     * Mounting configurations.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/odm.php'       => config_path('spiral/odm.php'),
            __DIR__ . '/../../config/tokenizer.php' => config_path('spiral/tokenizer.php')
        ]);
    }
}