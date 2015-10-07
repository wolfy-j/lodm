<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace LODM\Support;

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
     * @param string $prefix
     */
    public function __construct($prefix = 'spiral')
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($section = null)
    {
        if (!empty($this->prefix)) {
            $section = $this->prefix . '.' . $section;
        }

        return config($section);
    }
}