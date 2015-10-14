# LODM, ODM with inheritance for Laravel 5.1+ (beta)
[![Latest Stable Version](https://poser.pugx.org/wolfy-j/lodm/v/stable)](https://packagist.org/packages/wolfy-j/lodm) [![Total Downloads](https://poser.pugx.org/wolfy-j/lodm/downloads)](https://packagist.org/packages/wolfy-j/lodm) 
[![Latest Unstable Version](https://poser.pugx.org/wolfy-j/lodm/v/unstable)](https://packagist.org/packages/wolfy-j/lodm) [![License](https://poser.pugx.org/spiral/framework/license)](https://packagist.org/packages/wolfy-j/lodm)

LODM provides the ability to manage your MongoDB data in an OOP way using your models compositions, aggregations and **inheritance**. 

One of the side effects of this component design, is that you are able to create ODM models which are not related to MongoDB and use them to represent hierarchical data.

Functionality includes:
* Compositions (nested documents)
* Aggregations (related documents)
* Inheritance (models child in same collection/composition as parent)
* MongoDB Atomic operations
* Entity validations ([Laravel validator](https://github.com/wolfy-j/lodm/blob/master/source/LODM/Laravel/LaravelValidator.php) rules can be used)
* Read access (hidden fields)
* Write access (secure and fillable fields)
* JsonSerialization
* Field mutators (getters and setters)
* Field accessors (for example Carbon reader/writer for MongoDB fields)
* Magic getters, setters and methods

> Attention, at this moment pagination can only be performed manually, need help to integrate laravel paginator as `Spiral\Pagination\PaginatorInterface`.


## For Example

Base classes:
* `Spiral\ODM\DocumentEntity` - one piece of embeddable data.
* `Spiral\ODM\Document` - DocumentEntity with added ActiveRecord like functionality and link to MongoDB collection.

```php
class Post extends Document
{
    //Adds timeCreated and timeUpdated fields
    use TimestampsTrait;

    protected $schema = [
        '_id'      => 'MongoId',
        'author'   => Author::class,
        'tags'     => [Tag::class],
        
        //Aggregations (references)
        'comments' => [
            self::MANY => Comment::class,
            ['postId' => 'self::_id']
        ],
    ];
}
```

```php
class Author extends DocumentEntity
{
    protected $schema = [
        'name' => 'string',
    ];
}
```

Aggregation can be declared to ONE or MANY documents:

```php
class Comment extends Document
{
    //Adds timeCreated and timeUpdated fields
    use TimestampsTrait;

    protected $schema = [
        '_id'     => 'MongoId',
        'postId'  => 'MongoId',
        'author'  => Author::class,
        'message' => 'string',
        
        //Aggregations
        'post'    => [self::ONE => Post::class, ['_id' => 'self::postId']],
    ];
    
    protected $validates = [
        'message' => 'required'
    ];
}
```

Selection can be performed using the methods `find`, `findOne` and `findByPK`:

```php
foreach (Post::find() as $post) {
    dump($post->author->getName());
    
    echo $post->comments()->count();
}
```

You can create a new entity using the `new` keyword and `setFields` method or the static method `create`:

```php
$post = new Post([
    'author' => new Author(...)
]);
$post->setFields($this->request->all());

if(!$post->save()) {
    dump($post->getErrors());
}
```

ODM can also support MongoDB atomic operations using it's accessors and compositions:

```php
$post = Post::findByPK($mongoID);
$post->tags->push(new Tag());
$post->save();
```

## Installation
Package installation can be performed using the simple composer command `composer require wolfy-j/lodm`. 

To include ODM functionality in your application, you have to register the service provider  `Spiral\LODM\Laravel\ODMServiceProvider` and CLI command `Spiral\LODM\Commands\SchemaUpdate` in the app.php config and ConsoleKernel accordingly. The module provides two configuration files which describe the class location directories (by default whole application), the set of connected MongoDB databases (ODM does not use any of Laravel's database functionality) and options that can simplify document creation.

To publish the component configurations, simply execute `php artisan vendor:publish`. Now you can specify the database connection in the `config/spiral/odm.php` file:

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

> You can learn more about component configuration in it's official documentation.

YOu can also add `aliases` for ODM models in the config\app.php file:

```
    ...
    'Document'         => 'Spiral\ODM\Document',
    'DocumentEntity'   => 'Spiral\ODM\DocumentEntity', //Complex types
```

## Schema Updates
The Spiral ODM component utilizes behaviour schemas for it's entities. These technique are used to significantly increase your components performance without reducing it's functionality. Since the schema is stored in the permanent application memory, you must update it each time you make any changes to your `Document` or `DocumentEntity` models (like schema, default, fillable, validates, etc).

To update your ODM schema, simply execute: `php artisan odm:schema`

> Schema update is only one thing you have to do to make your models work.

## Documentation

To efficiently manipulate Documents, read about it's parent model [DataEntity](https://github.com/spiral/guide/blob/master/components/entity.md).
The documentation for the ODM Component with examples can be found here on [this page](https://github.com/spiral/guide/blob/master/odm/basics.md). Extended usage with compositions, aggreagations and inheratance is [located here] (https://github.com/spiral/guide/blob/master/odm/oop.md). To find out how to use Documents outside of MongoDB scope, check out the following [standalone tutorial](https://github.com/spiral/guide/blob/master/odm/standalone.md).

Other documentation articles related to Spiral ODM component:
* [The Design] (https://github.com/spiral/guide/blob/master/framework/design.md) 
* [**IoC Container**] (https://github.com/spiral/guide/blob/master/framework/container.md)
* [Application Memory (&#1000;)] (https://github.com/spiral/guide/blob/master/framework/memory.md)
* [**DataEntity Model**] (https://github.com/spiral/guide/blob/master/components/entity.md)
* [Pagination] (https://github.com/spiral/guide/blob/master/components/pagination.md)
* [Tokenizer] (https://github.com/spiral/guide/blob/master/components/tokenizer.md)
* [**Validation**] (https://github.com/spiral/guide/blob/master/components/validation.md) (attention, this module uses Laravel validator via `ValidatorInterface`!)
* [Behaviour Schemas] (https://github.com/spiral/guide/blob/master/schemas.md)

## Additional Tools
Module also provides the global function `dmp`, which is linked to the Spiral Dumper component and utilizes __defugInfo function of DataEntity model. This can simplify debugging as it will dump only valuable information:

```php
dmp($post);
```

## Issues
Please do not open issue tickets in this github project unless they are related to the integration process. Use [Components Respository](https://github.com/spiral/components) for ODM related issues.

## Dependencies
At this moment, this module depends on whole set of Spiral components (simply because they are all placed in the same repo) and their nested dependencies. However, this state is only kept until every component gets it's own repository (Please feel free to propose any ideas or suggestions for better ways to do this).

Project will be counted as **beta** until repository split is performed.

## Standalone usage
The Spiral ODM component can also be used outside of any framework as a standalone module. Just check what  configurations and container bindings are set in the service provider.
