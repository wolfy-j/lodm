<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\LODM\Support;

use Spiral\Core\MemoryInterface;
use Spiral\Files\FilesInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Simple application memory to be used outside of spiral core, for example in Laravel applications.
 *
 * @see https://github.com/spiral/guide/blob/master/framework/design.md
 * @see https://github.com/spiral/guide/blob/master/framework/memory.md
 * @see https://github.com/spiral/guide/blob/master/schemas.md
 */
class Memory implements MemoryInterface
{
    /**
     * Extension for memory files.
     */
    const EXTENSION = '.php';

    /**
     * Default memory location.
     *
     * @var string
     */
    private $directory = null;

    /**
     * Files are needed for write/read operations.
     *
     * @var FilesInterface
     */
    protected $files = null;

    /**
     * @param string         $directory
     * @param FilesInterface $files
     */
    public function __construct($directory, FilesInterface $files)
    {
        $this->directory = $directory;
        $this->files = $files;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $filename Cache filename.
     */
    public function loadData(string $section)
    {
        $filename = $this->memoryFilename($section);

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
    public function saveData(string $section, $data)
    {
        $filename = $this->memoryFilename($section);

        //We are packing data into plain php
        $data = '<?php return ' . var_export($data, true) . ';';

        //We need help to write file with directory creation
        $this->files->write($filename, $data, FilesInterface::RUNTIME, true);
    }

    /**
     * Get all memory sections belongs to given memory location (default location to be used if
     * none specified).
     *
     * @param string $location
     *
     * @return array
     */
    public function getSections($location = null)
    {
        if (!empty($location)) {
            $location = $this->directory . $location . '/';
        } else {
            $location = $this->directory;
        }

        if (!$this->files->exists($location)) {
            return [];
        }

        $finder = new Finder();
        $finder->in($location);

        /**
         * @var SplFileInfo $file
         */
        $sections = [];
        foreach ($finder->name("*" . static::EXTENSION) as $file) {
            $sections[] = substr($file->getRelativePathname(), 0, -1 * (strlen(static::EXTENSION)));
        }

        return $sections;
    }

    /**
     * Get extension to use for runtime data or configuration cache, all file in cache directory
     * will additionally get applicationID postfix.
     *
     * @param string $name Runtime data file name (without extension).
     *
     * @return string
     */
    private function memoryFilename($name)
    {
        $name = strtolower(str_replace(['/', '\\'], '-', $name));

        //Runtime cache
        return $this->directory . $name . static::EXTENSION;
    }
}