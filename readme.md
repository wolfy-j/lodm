LODM, ODM with inheritance for Laravel 5+
========
[![Latest Stable Version](https://poser.pugx.org/spiral/odm/v/stable)](https://packagist.org/packages/spiral/odm) 
[![License](https://poser.pugx.org/spiral/odm/license)](https://packagist.org/packages/spiral/odm)
[![Build Status](https://travis-ci.org/spiral/odm.svg?branch=master)](https://travis-ci.org/spiral/odm)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral/odm/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spiral/odm/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/spiral/odm/badge.svg?branch=master)](https://coveralls.io/github/spiral/odm?branch=master)

<b>[Documentation](http://spiral-framework.com/guide)</b> | [CHANGELOG](/CHANGELOG.md)

LODM module is intended to bring the Spiral ODM component functionality into your Laravel applications. This component provides the ability to manage your MongoDB data in an OOP way using your models compositions and aggregations.

## Installation
Package installation can be performed using the simple composer command `composer require wolfy-j/lodm`. 

To include ODM functionality in your application, you have to register the service provider  `Spiral\LODM\Laravel\ODMServiceProvider` and CLI command `Spiral\LODM\Commands\SchemaUpdate` in the app.php config and ConsoleKernel accordingly. The module provides two configuration files which describe the class location directories (by default whole application), the set of connected MongoDB databases (ODM does not use any of Laravel's database functionality) and options that can simplify document creation.

## Documentation
Full documentation with examples is available [here](https://spiral-framework.com/guide/odm-overview).

## Issues
Please do not open issue tickets in this github project unless they are related to the integration process. Use [Primary Respository](https://github.com/spiral/odm) for ODM related issues.

## Dependencies
At this moment, this module depends on whole set of Spiral components (simply because they are all placed in the same repo) and their nested dependencies. However, this state is only kept until every component gets it's own repository (Please feel free to propose any ideas or suggestions for better ways to do this).

Project will be counted as **beta** until repository split is performed.

## Standalone usage
The Spiral ODM component can also be used outside of any framework as a standalone module. Just check what  configurations and container bindings are set in the service provider.
