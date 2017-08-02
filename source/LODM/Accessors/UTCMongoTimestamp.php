<?php
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\LODM\Accessors;

use MongoDB\BSON\UTCDateTime;
use Spiral\ODM\CompositableInterface;

/**
 * Timezone is fixed for mongodb. Packs into MongoDate
 */
class UTCMongoTimestamp extends AbstractTimestamp implements CompositableInterface
{
    /**
     * {@inheritdoc}
     */
    protected function fetchTimestamp($value): int
    {
        return $this->castTimestamp($value, new \DateTimeZone('UTC')) ?? 0;
    }

    /**
     * @return \MongoDB\BSON\UTCDateTime
     */
    public function packValue()
    {
        return new UTCDateTime($this);
    }

    /**
     * {@inheritdoc}
     */
    public function buildAtomics(string $container = ''): array
    {
        return ['$set' => [$container => $this->packValue()]];
    }

    /**
     * Carbon migration.
     *
     * @param string $name
     *
     * @return int|null
     */
    public function __get($name)
    {
        if ($name == 'timestamp') {
            return $this->getTimestamp();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function castTimestamp($datetime, \DateTimeZone $timezone = null)
    {
        if ($datetime instanceof UTCDateTime) {
            $datetime = $datetime->toDateTime();
        }

        return parent::castTimestamp($datetime, $timezone);
    }
}