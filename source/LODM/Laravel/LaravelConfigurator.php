<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\LODM\Laravel;

use Spiral\Core\ConfigInterface;
use Spiral\Core\ConfiguratorInterface;

/**
 * A simple ConfiguratorInterface wrapper which uses laravel configs as source.
 */
class LaravelConfigurator implements ConfiguratorInterface
{
    /**
     * Configuration location prefix/namespace.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Cached configs.
     *
     * @var array
     */
    protected $configs = [];

    private $aliases = [
        'schemas/documents' => 'documents'
    ];

    /**
     * @param string $prefix
     */
    public function __construct($prefix = 'spiral')
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $toArray Always force array response.
     */
    public function getConfig(?string $section = null) : array
    {
        if(!empty($this->aliases[$section])) {
            $section = $this->aliases[$section];
        }

        if (!empty($this->prefix)) {
            $section = $this->prefix . '.' . $section;
        }

        $result = config($section);

        if(is_null($result)){
            return [];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createInjection(\ReflectionClass $class, string $context = null)
    {
        if (isset($this->configs[$class->getName()])) {
            return $this->configs[$class->getName()];
        }

        //Due internal contract we can fetch config section from class constant
        $config = $this->getConfig($class->getConstant('CONFIG'));

        if ($config instanceof ConfigInterface) {
            //Apparently config file contain class definition (let's hope this is same config class)
            return $config;
        }

        return $this->configs[$class->getName()] = $class->newInstance($config);
    }

    /**
     * Drop all cached configs (in RAM).
     */
    public function flushCache()
    {
        $this->configs = [];
    }
}
