Facile.it Coding Standard
-------------------------

Repository with all coding standard ruleset.


Installation
============

Currently, [Composer](https://getcomposer.org/) is the only supported installation tool.

```
$ composer require --dev facile-it/facile-coding-standard
```

When you install it, a plugin will ask you some questions to setup your project automatically.

The installer will add a `.php_cs.dist` file in your project root directory,
then you can edit manually if you need some changes.

The CS config will be configured to find your project files using
composer autoload sources.
Only `psr-0`, `psr-4` and `classmap` autoloads are supported.

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
$config->setUsingCache(false);

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

See [PhpCsFixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) GitHub page.


### v0.3 default configuration

```
<?php

/*
 * Additional rules or rules to override.
 * These rules will be added to default rules or will override them if the same key already exists.
 */
$additionalRules = [];

$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(), // risky rules
    new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
]);

$config = PhpCsFixer\Config::create();
$config->setRules($rulesProvider->getRules());

$finder = PhpCsFixer\Finder::create();
$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
$finder->in($autoloadPathProvider->getPaths());

$config->setFinder($finder);

return $config;
```