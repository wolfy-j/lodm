<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\LODM\Support;

use Interop\Container\ContainerInterface;
use Spiral\Core\Component;
use Spiral\Core\Container;

/**
 * Some spiral functions require global/static container, for example it provides you ability to
 * write code like:
 * new Post();
 *
 * In other scenario you with either be required to use document function of ODM component or
 * provide odm instance into your document explicitly:
 *
 * new Post([], null, $odm);
 */
class SharedContainer extends Component
{
    /**
     * Initiate global/static container which brings some sugar to code.
     *
     * @return ContainerInterface
     */
    public static function initContainer(ContainerInterface $container)
    {
        return self::staticContainer($container);
    }
}