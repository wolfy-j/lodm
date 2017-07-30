ODM with inheritance and OOP composition for Laravel 5+
========
[![Latest Stable Version](https://poser.pugx.org/spiral/odm/v/stable)](https://packagist.org/packages/spiral/odm) 
[![License](https://poser.pugx.org/spiral/odm/license)](https://packagist.org/packages/spiral/odm)
[![Build Status](https://travis-ci.org/spiral/odm.svg?branch=master)](https://travis-ci.org/spiral/odm)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral/odm/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spiral/odm/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/spiral/odm/badge.svg?branch=master)](https://coveralls.io/github/spiral/odm?branch=master)

<b>[Full Documentation](http://spiral-framework.com/guide)</b> | [CHANGELOG](https://github.com/spiral/odm/blob/master/CHANGELOG.md)

LODM module is intended to bring the Spiral ODM component functionality into your Laravel applications. This component provides the ability to manage your MongoDB data in an OOP way using your models compositions and aggregations.

## Installation
Package installation can be performed using the simple composer command `composer require wolfy-j/lodm`. 

To include ODM functionality in your application, you have to register the service provider  `Spiral\LODM\Laravel\ODMServiceProvider` and CLI command `Spiral\LODM\Commands\SchemaUpdate` in the app.php config and ConsoleKernel accordingly. The module provides two configuration files which describe the class location directories (by default whole application), the set of connected MongoDB databases (ODM does not use any of Laravel's database functionality) and options that can simplify document creation.

## Documentation
* [MongoDB Databases](https://spiral-framework.com/guide/odm/databases.md)
* [Documents and DocumentEntity](https://spiral-framework.com/guide/odm/entities.md)
* [Accessors and Filters](https://spiral-framework.com/guide/odm/accessors.md)
* [Repositories and Selectors](https://spiral-framework.com/guide/odm/repositories.md)
* [Scaffolding](https://spiral-framework.com/guide/odm/scaffolding.md)
* [Compositions and Aggregations](https://spiral-framework.com/guide/odm/oop.md)
* [Inheritance](https://spiral-framework.com/guide/odm/inheritance.md)

## Examples
```php
class User extends Document
{
  const SCHEMA = [
    '_id'            => \MongoId::class,
    'name'           => 'string',
    'email'          => 'string',
    'balance'        => 'float',
    'timeRegistered' => \MongoDate::class,
    'tags'           => ['string'],
    'profile'        => Profile::class,

    //Aggregations
    'posts'          => [
        self::MANY => Post::class,
        ['userId' => 'self::_id']
    ]
  ];
}
```

```php
protected function indexAction()
{
    $u = new User();
    $u->name = 'Anton';
    $u->email = 'test@email.com';
    $u->balance = 99;
    $u->save();

    dump($u);
}
```

```php
protected function indexAction(string $id, UsersRepository $users)
{
    $user = $users->findByPK($id);
    if (empty($user)) {
        throw new NotFoundException('No such user');
    }

    dump($user);
}
```

```php
$user = User::findOne();
$user->profile->biography = 'some bio';
$user->profile->facebookUID = 2345678;

$user->sessions->solidState(false);
$user->sessions->push(new Session([
    'timeCreated' => new \MongoDate(),
    'accessToken' => 'newrandom'
]));
```


## Issues
Please do not open issue tickets in this github project unless they are related to the integration process. Use [Primary Respository](https://github.com/spiral/odm) for ODM related issues.
