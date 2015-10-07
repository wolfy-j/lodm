<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace LODM\Support;

use LODM\Support\Exceptions\MemoryWriteException;
use Spiral\Core\HippocampusInterface;

/**
 * Application memory for Laravel applications.
 *
 * @see https://github.com/spiral/guide/blob/master/framework/design.md
 * @see https://github.com/spiral/guide/blob/master/framework/memory.md
 * @see https://github.com/spiral/guide/blob/master/schemas.md
 */
class LaravelMemory implements HippocampusInterface
{
    /**
     * Memory file extension.
     */
    const EXTENSION = 'php';

    /**
     * Default location to store memory files. Has to be writable.
     *
     * @var string
     */
    private $defaultLocation = '';

    /**
     * @param $defaultLocation
     */
    public function __construct($defaultLocation)
    {
        $this->defaultLocation = $defaultLocation;
    }

    /**
     * {@inheritdoc}
     */
    public function loadData($name, $location = null)
    {
        if (empty($location)) {
            $location = $this->defaultLocation;
        }

        $filename = $location . $name . '.' . static::EXTENSION;

        if (!file_exists($filename)) {
            return null;
        }

        try {
            return include($filename);
        } catch (\ErrorException $exception) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveData($name, $data, $location = null)
    {
        if (empty($location)) {
            $location = $this->defaultLocation;
        }
        $filename = $location . $name . '.' . static::EXTENSION;
        try {
            //We are going to store data in php form, in this OpCache will work for us
            file_put_contents($filename, '<?php return ' . var_export($data, true) . ';');
        } catch (\ErrorException $exception) {
            //To be better catchable
            throw new MemoryWriteException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}