# Spiral ODM for Laravel 5.1+
LODM module is intended to bring Spiral ODM component functionality into Laravel applications. Component provides ability to manage your MongoDB data in OOP way using compositions and aggregations of your models. One of the side effects of component design makes you able to create ODM models which are not related to MongoDB and use them to represent iehahical data.

Functionality includes:
* Compositions (nested documents)
* Aggregations (related documents)
* Entity validations (laravel validator rules can be used)
* Read access (hidden fields)
* Write access (secure and fillable fields)
* JsonSerialization
* Field mutators (getters and setters)
* Field accessors (for example Carbon reader/writer for MongoDB fields)
* Magic getters, setters and methods

## Installation
Package installation can be performed using simple composer command `composer require wolfy-j/lodm`. Module provides two configuration files using to describe class location directories (by default whole application), set of connected MongoDB databases (ODM does not use any of Laravel database functionality) and options used to simplify document creation.

To publish component configurations, simply execute `php artisan vendor:publish`. Now you are able to specify database connection in `config/spiral/odm.php` file:

```php
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
            'int'       => ['setter' => 'intval'],
            'float'     => ['setter' => 'floatval'],
            'string'    => ['setter' => 'strval'],
            'bool'      => ['setter' => 'boolval'],
            'MongoId'   => ['setter' => [ODM::class, 'mongoID']],
            'array'     => ['accessor' => ScalarArray::class],
            'timestamp' => ['accessor' => \Spiral\LODM\Accessors\MongoTimestamp::class],
            'MongoDate' => ['accessor' => \Spiral\LODM\Accessors\MongoTimestamp::class]
        ],
        'mutatorAliases' => [
        ]
    ]
];
```

To make ODM functionality available in your application you have to register `Spiral\LODM\Laravel\ODMServiceProvider` service provider and CLI command `Spiral\LODM\Commands\SchemaUpdate` in app.php config and ConsoleKernel accordingly.

> You can read more about componenet configuration in it's official documentation.

## Schema Updates
Spiral ODM component utilizes so called behaviour schemas for it's entities, such technique used to singnificantly increate component performance without recuding it's functionality. Since schema stored in permanent application memory you have to update it every time you doing changes any of you `Document` or `DocumentEntity` models (like schema, default, fillable, validates and etc).

To update ODM schema simply execute: `php artisan odm:schema`

## Examples
TODO

> Document is NOT ActiveRecord (even if it looks so) NEVER put client data into constructor you either have to use static method `create` or `setFields` of your entity.

See documenation to get more information.

## Documentation

Documentation for ODM Component with examples can be found on [this page](https://github.com/spiral/guide/blob/master/odm/overview.md) - WILL BE UPDATED THIS WEEK (in my drafts for now). To find how to use Documents outside of MongoDB scope check this [documentation](https://github.com/spiral/guide/blob/master/odm/standalone.md) - IN DRAFT.

You can also find list of available spiral components including Templater, ORM, Storage Manager and etc [here](https://github.com/spiral/components).

Other documentation articles related to Spiral ODM component:
* [The Design] (https://github.com/spiral/guide/blob/master/framework/design.md) 
* [**IoC Container**] (https://github.com/spiral/guide/blob/master/framework/container.md)
* [Application Memory (&#1000;)] (https://github.com/spiral/guide/blob/master/framework/memory.md)
* [**DataEntity Model**] (https://github.com/spiral/guide/blob/master/components/entity.md)
* [Pagination] (https://github.com/spiral/guide/blob/master/components/pagination.md)
* [Tokenizer] (https://github.com/spiral/guide/blob/master/components/tokenizer.md)
* [**Validation**] (https://github.com/spiral/guide/blob/master/components/validation.md)
* [Behaviour Schemas] (https://github.com/spiral/guide/blob/master/schemas.md)

## Issues
Please do not open issue tickets in this github project unless they are related to integration process. Use [Components Respository](https://github.com/spiral/components) for ODM related issues.

## Dependencies
At this moment such module depends on whole set of spiral components and their nested dependencies, however this state is kept only until every component will get it's own repository (feel free to propose your help or suggestion).
