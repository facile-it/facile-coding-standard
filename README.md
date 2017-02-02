Facile.it Coding Standard
-------------------------

Repository with all coding standard ruleset.


Status
======

Under development


Installation
============

1. Install the module via composer by running:

```
$ composer require --dev facile-it/facile-coding-standard
```

2. Add composer scripts into `composer.json`:

```php
"scripts": {
  "cs-check": "php-cs-fixer fix --dry-run --diff",
  "cs-fix": "php-cs-fixer fix --diff"
}
```

3. Create file `.php_cs.dist` on base path of your repository with the following content:

```php
<?php

/** @var PhpCsFixer\ConfigInterface $config **/
$config = include __DIR__ . '/vendor/facile-it/facile-coding-standard/.php_cs';

$finder = PhpCsFixer\Finder::create();
$finder->in([
    __DIR__.'/src', // source path
]);

$config->setFinder($finder);

return $config;

```

Then you can customize `PhpCsFixer` configuration.

Example:

```php
<?php

/** @var PhpCsFixer\ConfigInterface $config **/
$config = include __DIR__ . '/vendor/facile-it/facile-coding-standard/.php_cs';

$finder = PhpCsFixer\Finder::create();
$finder->in([
    __DIR__.'/src',
    __DIR__.'/config',
]);

$config->setFinder($finder);

// enable cache (default disabled)
$config->setUsingCache(true);
// set cache file
$config->setCacheFile(__DIR__ . '/.php_cs.cache');
// hide progress
$config->setHideProgress(true);

// Adding rules
$rules = $config->getRules();
$rules['ordered_imports'] = true;
$config->setRules($rules);

return $config;

```

### PhpCsFixer configuration

Seet [PhpCsFixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) GitHub page.