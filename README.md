# graphql-php-client
[![Current Release](https://img.shields.io/badge/release-0.2.0-1eb0fc.svg)](https://github.com/Hearst-Hatchery/graphql-php-client/releases/tag/0.2.0)
[![Build Status](https://travis-ci.com/Hearst-Hatchery/graphql-php-client.svg?branch=master)](https://travis-ci.com/Hearst-Hatchery/graphql-php-client)
[![codecov](https://codecov.io/gh/Hearst-Hatchery/graphql-php-client/branch/master/graph/badge.svg)](https://codecov.io/gh/Hearst-Hatchery/graphql-php-client)


A GraphQL client in PHP. This uses Guzzle, but it's compatible with any [PSR-18](http://www.php-fig.org/psr/psr-18/) client through [HTTPlug](http://httplug.io/)

## Installation

```bash
composer require hearst-hatchery/graphql-php-client
```

## Usage

```php
require 'vendor/autoload.php';
use GraphQLClient\Client;

$client = new Client("https://swapi.graph.cool/");

$query = <<<QUERY
{
  allPersons(first:2) {
    name
    birthYear
    gender
    homeworld {
      name
    }
  }
}
QUERY;

$response = $client->query($query);

var_dump($response);
```
this will return

```
array(1) {
  ["allPersons"]=>
  array(2) {
    [0]=>
    array(4) {
      ["name"]=>
      string(14) "Luke Skywalker"
      ["birthYear"]=>
      string(5) "19BBY"
      ["gender"]=>
      string(4) "MALE"
      ["homeworld"]=>
      array(1) {
        ["name"]=>
        string(8) "Tatooine"
      }
    }
    [1]=>
    array(4) {
      ["name"]=>
      string(5) "C-3PO"
      ["birthYear"]=>
      string(6) "112BBY"
      ["gender"]=>
      string(7) "UNKNOWN"
      ["homeworld"]=>
      array(1) {
        ["name"]=>
        string(8) "Tatooine"
      }
    }
  }
}
```

### Advanced Example

You can easily extend the client, and it supports Httplug's Plugins. This one uses Github's API and variables.

This example needs `composer require php-http/message` before running.
Adapted from https://developer.github.com/v4/guides/forming-calls/#working-with-variables

```php
require 'vendor/autoload.php';
use GraphQLClient\Client;
use Http\Message\Authentication\Bearer;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;

class GithubClient extends Client
{
    public function __construct(){
        parent::__construct("https://api.github.com/graphql");
    }

    protected function buildClient(array $options = [])
    {
        $authentication = new Bearer('<Github API Token>');
        $authenticationPlugin = new AuthenticationPlugin($authentication);

        return new PluginClient(
            parent::buildClient($options),
            [$authenticationPlugin]
        );
    }
}

$client = new GithubClient();

$query = <<<QUERY
query(\$number_of_repos:Int!) {
  viewer {
    name
     repositories(last: \$number_of_repos) {
       nodes {
         name
       }
     }
   }
}
QUERY;

$response = $client->query($query, ['number_of_repos' => 3]);

var_dump($response);
```


### Client options

Currently, this client accepts all [Guzzle request options](http://docs.guzzlephp.org/en/stable/request-options.html).

#### Default options

* `method` : `POST`
* `headers` : `['Content-Type: application/json']`
* `json` : `true`

## Using our GraphQL Query Builder

We also use a query builder in our applications (https://github.com/Hearst-Hatchery/graphql-php-query-builder). Using the first example, installation is easy:

```bash
composer require hearst-hatchery/graphql-php-query-builder
```

Then include it
```php
use GraphQLQueryBuilder\QueryBuilder;
```

Then `$query` can be shortened to:

```php
$query->setObjectField('allPersons')->setArguments(['first' => 2])->setQueryObject([
  'name',
  'birthYear',
  'gender',
  'homeworld' => ['name']
]);
```

And expand it in our `query()` call:
```php
$response = $client->query($query->buildQuery());
```

Which should give the same result, while having access to dynamic GraphQL queries!

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on submitting pull requests to us.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
