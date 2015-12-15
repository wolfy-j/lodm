<?php
/**
 * ODM databases and schema builder configuration.
 *
 * @see ODMConfig
 */
use Spiral\LODM\Accessors;
use Spiral\ODM\Accessors\ScalarArray;
use Spiral\ODM\Entities\MongoDatabase;
use Spiral\ODM\ODM;

return [
    /*
    * Here you can specify name/alias for database to be treated as default in your application.
    * Such database will be returned from ODM->database(null) call and also can be
    * available using $this->db shared binding.
    */
    'default'   => 'default',
    'aliases'   => [
        'database' => 'default',
        'db'       => 'default',
        'mongo'    => 'default'
    ],
    'databases' => [
        'default' => [
            'server'    => 'mongodb://localhost:27017',
            'profiling' => MongoDatabase::PROFILE_SIMPLE,
            'database'  => 'spiral',
            'options'   => [
                'connect' => true
            ]
        ],
    ],
    'schemas'   => [
        /*
         * Set of mutators to be applied for specific field types.
         */
        'mutators'       => [
            'int'       => ['setter' => 'intval'],
            'float'     => ['setter' => 'floatval'],
            'string'    => ['setter' => 'strval'],
            'long'      => ['setter' => 'intval'],
            'bool'      => ['setter' => 'boolval'],
            'MongoId'   => ['setter' => [ODM::class, 'mongoID']],
            'array'     => ['accessor' => ScalarArray::class],
            'timestamp' => ['accessor' => Accessors\MongoTimestamp::class],
            'MongoDate' => ['accessor' => Accessors\MongoTimestamp::class]
        ],
        'mutatorAliases' => [
            /*
             * Mutator aliases can be used to declare custom getter and setter filter methods.
             */
        ]
    ]
];