<?php
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\LODM\Traits;

use MongoDB\BSON\UTCDateTime;
use Spiral\Models\Events\DescribeEvent;
use Spiral\Models\Events\EntityEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Timestamps traits adds two magic fields into model/document schema time updated and time created
 * automatically populated when entity being saved. Can be used in Models and Documents.
 *
 * ORM: time_created, time_updated
 * ODM: timeCreated, timeUpdated
 */
trait TimestampsTrait
{
    /**
     * Touch object and update it's time_updated value.
     *
     * @return $this
     */
    public function touch()
    {
        $this->setField('timeUpdated', new UTCDateTime(time()));

        return $this;
    }

    /**
     * Called when model class are initiated.
     */
    protected static function __init__timestamps()
    {
        /**
         * Updates values of time_updated and time_created fields.
         */
        $listener = self::__timestamps__saveListener();
        self::events()->addListener('create', $listener);
        self::events()->addListener('update', $listener);
    }

    /**
     * When schema being analyzed.
     */
    protected static function __describe__timestamps()
    {
        self::events()->addListener('describe', self::__timestamps__describeListener());
    }

    /**
     * DataEntity save.
     *
     * @return \Closure
     */
    private static function __timestamps__saveListener()
    {
        return function (EntityEvent $event, $eventName) {
            $entity = $event->getEntity();
            switch ($eventName) {
                case 'create':
                    $entity->setField('timeCreated', new UTCDateTime(time() * 1000));
                //no-break
                case 'update':
                    $entity->setField('timeUpdated', new UTCDateTime(time() * 1000));
            }
        };
    }

    /**
     * Create appropriate schema modification listener. Executed only in analysis.
     *
     * @return callable
     */
    private static function __timestamps__describeListener()
    {
        return function (DescribeEvent $event) {
            if ($event->getProperty() != 'schema') {
                return;
            }

            $schema = $event->getValue();
            $schema += [
                'timeCreated' => 'timestamp',
                'timeUpdated' => 'timestamp'
            ];

            //Updating schema value
            $event->setValue($schema);
        };
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    abstract public static function events(): EventDispatcherInterface;
}