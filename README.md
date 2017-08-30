Facile.it Coding Standard
-------------------------

Repository with all coding standard ruleset.


Status
======

Under development


Installation
============

Currently, [Composer](https://getcomposer.org/) is the only supported installation tool.

```
$ composer require --dev facile-it/facile-coding-standard
```

When you install it, a plugin will ask you some questions to setup your project automatically.

The installer will add a `.php_cs.dist` file in your project root directory,
then you can edit manually if you need some changes.

The CS config will be configured to find your project files using composer autoload (psr-0, psr-4) sources.

The installer will also add two scripts in your `composer.json`;

```php
"scripts": {
  "cs-check": "php-cs-fixer fix --dry-run --diff",
  "cs-fix": "php-cs-fixer fix --diff"
}
```

Configuration
=============

The installation configuration should be enough to use it.

If you need to change the CS config file, we suggest to don't edit the main `.php_cs.dist` file.

You can create a new file `.php_cs` with something like this:

```php
<?php

/** @var PhpCsFixer\Config $config */
$config = require __DIR__ . '/.php_cs.dist';

// change your configuration...

return $config;
```

Usage
=====

To start code style check:

```
$ composer cs-check
```

To automatically fix code style:

```
$ composer cs-fix
```

### PhpCsFixer configuration

Seet [PhpCsFixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) GitHub page.