# Contributing


## Project Setup

### Development Environment

Fork this project, and install dependencies with composer

```bash
cd graphql-php-client
composer install
```

Upon install `bin/setup.sh` will run, which will add a pre-commit hook that lints any staged php files.

### Running tests

[Codeception](https://codeception.com/) is used for testing. We include a few shortcuts for building and debugging tests.

All all unit tests will be executed by running:

```bash
composer build
composer test
```

#### All test shortcuts

* `composer build` will generate a test helper for Codeception.
* `composer test` will run all unit tests
* `composer debug` will run all unit tests with verbose debugging
* `composer coverage` will run all tests and generate a coverage report
* `composer coverage-xml` same as `composer coverage` but the report will be in xml format, usually meant to be sent to coverage software/services

### Coding style

We use [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to lint our code. This repo includes [a custom `.php_cs.dist` file], which is a mix of [PSR-2](https://www.php-fig.org/psr/psr-2/) and a few other settings. To lint code, simply run:

```bash
composer lint
```
This command also runs any php file that's staged for commit. See above.

