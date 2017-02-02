Facile.it Coding Standard
-------------------------

This is a repository containing a tool to enforce company coding style standards.


## Status

Under development

Currently consist of just a Bash wrapper for [PHP CS Fixer][PHP CS Fixer] and its ruleset.

Future development could possibly include an [ESlint](http://eslint.org/) wrapper. 


## Installation

Currently, [Composer](https://getcomposer.org/) is the only supported installation tool.

```
$ composer require --dev facile/facile-coding-standard
```

## Usage

Invoke the script via CLI:

```
$ ./vendor/bin/cs-tool
```

The default command is `check`, and it will:

- load the ruleset provided by this repo;
- load a local `.php_cs` file if present (this will ignore the provided ruleset);
- look for files into `src` and `test` directories;
- perform a verbose *dry run* of the fixer.
 
 
For clarity, the actual PHP-CS-Fixer command is printed just before being executed.

You can also customise it further by adding options *after the desired command*:

```
$ ./vendor/bin/cs-tool check --rules=@SYMFONY --format=json
```

By the way, to actually fix stuff invoke the `fix` command

```
$ ./vendor/bin/cs-tool fix
```

Bear in mind that this will actually *edit* your files, so be responsible.


### Contributions

If you want to discuss the ruleset, please use the issue tracker of this repository.

### Further informations

For the moment, please refer to the official [PHP CS Fixer][PHP CS Fixer] documentation.


[PHP CS Fixer]: https://github.com/FriendsOfPHP/PHP-CS-Fixer
