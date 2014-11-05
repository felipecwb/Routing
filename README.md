Routing
=======

[![Build Status](https://travis-ci.org/felipecwb/Routing.svg?branch=master)](https://travis-ci.org/felipecwb/Routing)

More one simple Routing library for PHP.

*You'll need know about [Regex Patterns](http://php.net/manual/en/pcre.pattern.php).*

## Instalation
[Composer](https://packagist.org/packages/felipecwb/routing):
```json
{
    "felipecwb/routing": "dev-master"
}
```

**Example**:
```php
<?php

$router = new Router(
    new Matcher(
        new RouteCollection()
    )
);

$router->add(new Route('|/|', function () {
    echo "Hello World!";
});

$router->add(new Route('|/hello/(\w+)|', function ($name) {
    echo "Hello {$name}!";
});

$route = $router->match('/');
$route->call(); // output: Hellow World!
// with arguments
$route = $router->match('/hello/felipecwb');
$route->call(); // output: Hellow felipecwb!
```

[Look in tests for more explanation](tests)

## Contributions

**Feel free to contribute.**

1. Create a issue.
2. Follow the PSR-2 and PSR-4
3. PHPUnit to tests

> License MIT
