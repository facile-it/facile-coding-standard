<?php

declare(strict_types=1);

namespace Facile\CodingStandardsTest;

class Util
{
    public static function getComposerContent(): string
    {
        return <<<'JSON'
            {
              "name": "facile-it/facile-coding-standard-test",
              "description": "Facile coding standard test",
              "type": "project",
              "license": "proprietary",
              "keywords": [
                "facile.it"
              ],
              "homepage": "http://www.facile.it/",
              "support": {
                "email": "thomas.vargiu@facile.it"
              },
              "config": {
                "sort-packages": true
              },
              "require": {
                "php": "^7.0",
                "roave/security-advisories": "dev-master"
              },
              "require-dev": {
                "phpunit/phpunit": "^6.0"
              },
              "autoload": {
                "psr-4": {
                  "Application\\": [
                    "src/"
                  ]
                }
              },
              "autoload-dev": {
                "psr-4": {
                  "ApplicationTest\\": [
                    "tests/"
                  ]
                }
              }
            }

            JSON;
    }
}
