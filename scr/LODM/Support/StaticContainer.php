<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\LODM\Support;

use Spiral\Core\Component;
use Spiral\Core\ContainerInterface;

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
class StaticContainer extends Component
{
    /**
     * Mount global container. staticContainer method is protected and available only from
     * components, so we have to define wrapper method.
     *
     * @param ContainerInterface $container
     */
    public static function mountContainer(ContainerInterface $container)
    {
        self::staticContainer($container);
    }
}