# Hawkbit Presentation

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]
[![Coverage Status][ico-coveralls]][link-coveralls]

Ployglott and extensible presentation layer for different presentation engines.
The presentation layer uses [`league/plates`](http://platesphp.com/) as default engine and could be extended with Twig, 
Smarty, Liquif, blade and further more.

## Install

### Using Composer

Hawkbit Presentation is available on [Packagist][link-packagist] and can be installed using [Composer](https://getcomposer.org/). This can be done by running the following command or by updating your `composer.json` file.

```bash
composer require hawkbit/Presentation
```

composer.json

```javascript
{
    "require": {
        "hawkbit/Presentation": "~1.0"
    }
}
```

Be sure to also include your Composer autoload file in your project:

```php
<?php

require __DIR__ . '/vendor/autoload.php';
```

### Downloading .zip file

This project is also available for download as a `.zip` file on GitHub. Visit the [releases page](https://github.com/hawkbit/Presentation/releases), select the version you want, and click the "Source code (zip)" download button.

### Requirements

The following versions of PHP are supported by this version.

* PHP 5.5
* PHP 5.6
* PHP 7.0
* PHP 7.1
* HHVM

In addition to PHP you also need a valid [PSR-7](https://packagist.org/providers/psr/http-message-implementation)
and [PSR-11](https://packagist.org/providers/psr/container-implementation) integration.

[Hawkbit Micro Framework](https://github.com/HawkBitPhp/hawkbit) is supported by default.

[Silex](https://silex.sensiolabs.org/), 
[Lumen](https://lumen.laravel.com/), 
[zend-expressive](https://docs.zendframework.com/zend-expressive/) 
and [Slim](https://www.slimframework.com/) support is untested but should work as well.

## Setup

Setup with an existing application configuration (we refer to [tests/assets/config.php](tests/assets/config.php))

```php
<?php

use \Hawkbit\Application;
use \Hawkbit\Presentation\PresentationService;
use \Hawkbit\Presentation\Adapters\PlatesAdapter;
use \Hawkbit\Presentation\Adapters\Adapter;

$app = new Application(require './config.php');

// or configure manually

$app = new Application();

$app[Adapter::class] = new PlatesAdapter([
    'default' => __DIR__ . '/path/to/templates',
    'another' => __DIR__ . '/path/to/other/templates',
]);

$app[PresentationService::class] = new PresentationService($app->getContainer());

```

### Presentation from Hawbit Application

```php
<?php

/** @var \Hawkbit\Presentation\PresentationService $Presentation */
$service = $app[\Hawkbit\Presentation\PresentationService::class];

```

### Presentation in a Hawkbit controller

Access a presentation service in controller. Hawkbit inject classes to controllers by default.

```php
<?php

use Hawkbit\Presentation\PresentationService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MyController
{
    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * TestInjectableController constructor.
     * @param PresentationService $presentationService
     */
    public function __construct(PresentationService $presentationService)
    {
        $this->presentationService = $presentationService;
    }

    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        $response->getBody()->write($this->presentationService->render('index', ['world' => 'World']));
        return $response;
    }
}
```

### Access and extend engine

In some cases you would like to extend or access plates. We recommend to extend plates
at a central point of your application like bootstrap or even better in your project service provider.

```php
<?php

use Hawkbit\Presentation\PresentationService;

/** @var PresentationService $service */
$service = $app->getContainer()->get(PresentationService::class);
$service->getEngine()
    ->addFolder('acme', __DIR__ . '/templates/acme')
    ->registerFunction('uppercase', function ($string) {
        return strtoupper($string);
    });

```

### Wrap into PSR 7

Hawkbit presentation provides a PSR-7 Wrapper to capture rendered output into psr 7 response.


Please keep in mind to add an additional [PSR-7](https://packagist.org/providers/psr/http-message-implementation)
 implementation!

You just need to wrap you favorite presentation adapter into psr 7 adapter

```php
<?php

use \Hawkbit\Application;
use \Hawkbit\Presentation\PresentationService;
use \Hawkbit\Presentation\Adapters\PlatesAdapter;
use \Hawkbit\Presentation\Adapters\Adapter;
use \Hawkbit\Presentation\Adapters\Psr7WrapperAdapter;

$app = new Application(require './config.php');

// or configure manually

$app = new Application();

$app[Adapter::class] = new Psr7WrapperAdapter(new PlatesAdapter([
    'default' => __DIR__ . '/path/to/templates',
    'another' => __DIR__ . '/path/to/other/templates',
]), 
$app[\Psr\Http\Message\ServerRequestInterface::class], 
$app[\Psr\Http\Message\ResponseInterface::class]);

$app[PresentationService::class] = new PresentationService($app->getContainer());

```

The integrations works with examples mentioned above

#### Rendering

Please keep in mind, that the render method is now returning an instance of `\Psr\Http\Message\ResponseInterface` 
instead of a string!

Your presentation logic e. g. in a controller is now reduced as follows

```php
<?php

use Hawkbit\Presentation\PresentationService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MyController
{
    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * TestInjectableController constructor.
     * @param PresentationService $presentationService
     */
    public function __construct(PresentationService $presentationService)
    {
        $this->presentationService = $presentationService;
    }

    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        // configured with PSR-7 adapter
        return $this->presentationService->render('index', ['world' => 'World']);
    }
}
```

#### Add response on render

Response class is attached while rendering by default. But in some cases you need to 
add your own response class just before rendering. The wrappers render method takes 
optional response as a third argument.

```php
<?php

use Hawkbit\Presentation\PresentationService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MyController
{
    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * TestInjectableController constructor.
     * @param PresentationService $presentationService
     */
    public function __construct(PresentationService $presentationService)
    {
        $this->presentationService = $presentationService;
    }
    
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        // manipulate response
        // for example we need to add an api key
        $response = $response->withHeader('API-KEY', 123);
        
        // configured with PSR-7 adapter
        return $this->presentationService->render('index', ['world' => 'World'], $response);
    }
}
```

You are now able to [render a different view](http://platesphp.com/engine/folders/) e.g. `$presentationService->render('acme::index')` 
and use a [view helper function](http://platesphp.com/engine/functions/) within an view (template).

### Plates

Please refer to [plates documentation](http://platesphp.com) for more details.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email <mjls@web.de> instead of using the issue tracker.

## Credits

- [Marco Bunge](https://github.com/mbunge)
- [Jonathan Reinik (Plates)](https://github.com/reinink)
- [All contributors](https://github.com/hawkbit/Presentation/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hawkbit/presentation.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/HawkBitPhp/hawkbit-presentation/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hawkbit/presentation.svg?style=flat-square
[ico-coveralls]: https://img.shields.io/coveralls/HawkBitPhp/hawkbit-presentation/master.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/hawkbit/presentation
[link-travis]: https://travis-ci.org/HawkBitPhp/hawkbit-presentation
[link-downloads]: https://packagist.org/packages/hawkbit/presentation
[link-author]: https://github.com/mbunge
[link-contributors]: ../../contributors
[link-coveralls]: https://coveralls.io/github/HawkBitPhp/hawkbit-presentation
