# PHP Hooks for Dredd API Testing Framework
[![Build Status](https://travis-ci.org/ddelnano/dredd-hooks-php.svg?branch=master)](https://travis-ci.org/ddelnano/dredd-hooks-php)

##About
This package contains a PHP Dredd hook handler which provides a bridge between the [Dredd API Testing Framework](http://dredd.readthedocs.org/en/latest/)
 and PHP environment to ease implementation of testing hooks provided by [Dredd](http://dredd.readthedocs.org/en/latest/). Write Dredd hooks in PHP to glue together [API Blueprint](https://apiblueprint.org/) with your PHP project

Not sure what these Dredd Hooks are?  Read the Dredd documentation on [them](http://dredd.readthedocs.org/en/latest/hooks/)

The following are a few examples of what hooks can be used for:

- loading db fixtures
- cleanup after test step or steps
- handling authentication and sessions
- passing data between transactions (saving state from responses to stash)
- modifying request generated from blueprint
- changing generated expectations
- setting custom expectations
- debugging via logging stuff


Example

```php
<?php

use Dredd\Hooks;

Hooks::beforeAll(function(&$transaction) {

    // do any necessary setup
});
```
##Installing

###Composer

`dredd-hooks-php` can be easily installed through the use of [Composer](https://getcomposer.org/).

`composer require dredd-hooks-php --dev`

##Usage

1. Create a hook file in `hooks.php`

```php

use Dredd\Hooks;

Hooks::before("/test > GET", function(&$transaction) {

    // do any before setup necessary
});
```

**Very Important**  Please make sure the closure passed to any `Dredd\Hooks` method uses a reference for the `$transaction` variable!!
This is necessary so that the `$transaction` variable does not need to be returned from the closure in order to persist changes to the variable
in the closure's local scope.


2. Run it with dredd

`dredd apiary.apib localhost:3000 --language dredd-hooks-php --hookfiles ./hooks.php`

##API

The `Dredd\Hooks` class provides the following methods `before`, `after`, `before_all`, `after_all`, `before_each`, `after_each`, `before_validation`, and `before_each_validation`.
These methods correspond to the events that Dredd will run as it makes requests to the API endpoints defined in the blueprint/apiary.apib file.
The `before`, `before_validation` and `after` hooks are identified by [transaction name](http://dredd.readthedocs.org/en/latest/hooks/#getting-transaction-names)

##How to Contribute

1. Fork it
2. Create your feature branch (git checkout -b my-newfeature)
3. Commit your changes (git commit -am 'Add some feature')
4. Push (git push origin my-new-feature)
5. Create a new Pull Request

##Tests

When making a contribution it is very important to not break existing functionality.  This project uses PHPUnit for unit testing and
uses ruby based aruba.  
 
The test suite can be run by following these steps:
 
1. Install PHPUnit and cucumber locally. From the project root directory run

   `composer install`
   
   `bundle install`
   
2. Execute PHPUnit tests
   
   `vendor/bin/phpunit`
   
3. Run aruba/cucumber tests
   
   `bundle exec cucumber`
   
More details about the integration test can be found in the [dredd-hooks-template repo](https://github.com/apiaryio/dredd-hooks-template)   

##Further Details

For examples and more information please visit the [wiki](https://github.com/ddelnano/dredd-hooks-php/wiki)

# Todo
- Add framework agnostic example/more examples
- Explain entire dredd process to end user
