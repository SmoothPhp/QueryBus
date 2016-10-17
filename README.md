# Query Bus

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


## Install

Via Composer

``` bash
$ composer require smoothphp/querybus
```

## Usage
The query bus exists to execute queries within the domain of the application. Typically these are read only commands, with write operations being performed using the [Command Bus](https://github.com/SmoothPhp/CommandBus).  

The QueryBus is a simple concept, and leaves the majority of the implementation decisions to the developer. A simple implementation is provided for Laravel users.

The query bus exists of 3 components.

* Query  
  The DTO containing the intent and parameters for the query.
* Query Bus  
  Takes a query object, resolves the query handler, and executes it.
* Query Translator  
  Takes a query, and translates to the query handler class name.


### Laravel Users
The Laravel Query bus takes a Query object, and resolves the handler by adding 'Handler' to the class name. This handler class is then resolved by the container and all dependencies are injected.  
  
For example, `App\Queries\FindUserById` is resolved to `App\Queries\FindUserByIdHandler`, and the `handle` method is executed.  
You are free to implement query handler resolution however you like, though.

#### Service Provider
```php
<?php

return [
    // ...
    
    'providers' => [
        // ...
        SmoothPhp\QueryBus\Laravel\LaravelQueryBusServiceProvider::class,
    ],
];

```
#### Query
```php
<?php

class FindUserById {
    public $id;
    public function __construct(string $id) {
        $this->id = $id;
    }
}
```

#### Query Handler
```php
<?php

class FindUserByIdHandler {
    private $client;
    
    public function __construct(DBClient $client) {
        $this->client = $client;
    }
    
    public function handle(FindUserById $query) {
        return $this->client->table('users')->where('id', $query->id)->get();
    }
}
```

#### Using
```php
<?php

class ExampleController extends Controller {
    private $bus;
    
    public function __construct(\SmoothPhp\QueryBus\QueryBus $queryBus) {
        $this->bus = $queryBus;
    }
    
    public function showUser(string $userId) {
        return view('users.show')->with('user', $this->bus->query(new FindUserById($userId)));
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email simon@smoothphp.com instead of using the issue tracker.

## Credits

- [Simon Bennett][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/smoothphp/querybus.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/smoothphp/querybus/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/smoothphp/querybus.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/smoothphp/querybus.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/smoothphp/querybus.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/smoothphp/querybus
[link-travis]: https://travis-ci.org/smoothphp/querybus
[link-scrutinizer]: https://scrutinizer-ci.com/g/smoothphp/querybus/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/smoothphp/querybus
[link-downloads]: https://packagist.org/packages/smoothphp/querybus
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
