Routing
=======

More One Simple Router for PHP.

*You'll need know about Regex Patterns.*

Example:
```php
<?php

$router = new Router();

$router->add(new Route('|/|', function () {
    echo "Hello World!";
});

$router->add(new Route('|/hello/(\w+)|', function ($name) {
    echo "Hello {$name}!";
});

$route = $router->find('/');
$route->call(); // output: Hellow World!
// with arguments
$route = $router->find('/hello/felipecwb');
$route->call(); // output: Hellow felipecwb!
```

### Contributions

**Feel free to contribute.**

1. Create a issue.
2. Follow the PSR-2 and PSR-4
3. PHPUnit to tests

> License MIT
