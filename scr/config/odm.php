<?php
/**
 * ODM component configuration and mapping.
 * - default database alias/name
 * - list of mongo databases associated with their server, name, profiling mode and options
 * - list of database name aliases used for injections and other operations
 * - ODM SchemaBuilder configuration
 *      - set of default mutators associated with field type
 *      - mutator aliases to be used in model definitions
 */
use Spiral\ODM\Accessors\ScalarArray;
use Spiral\ODM\Entities\MongoDatabase;
use Spiral\ODM\ODM;

return [
    'default'   => 'default',
    'databases' => [
        'default' => [
            'server'    => 'mongodb://localhost:27017',
            'profiling' => MongoDatabase::PROFILE_SIMPLE,
            'database'  => 'spiral-empty',
            'options'   => [
                'connect' => true
            ]
        ]
    ],
    'aliases'   => [
        'database' => 'default',
        'db'       => 'default',
        'mongo'    => 'default'
    ],
    'schemas'   => [
        'mutators'       => [
            'int'     => ['setter' => 'intval'],
            'float'   => ['setter' => 'floatval'],
            'string'  => ['setter' => 'strval'],
            'bool'    => ['setter' => 'boolval'],
            'MongoId' => ['setter' => [ODM::class, 'mongoID']],
            'array'   => ['accessor' => ScalarArray::class]
        ],
        'mutatorAliases' => [
        ]
    ]
];