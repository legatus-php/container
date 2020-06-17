Legatus Container
=================

Flexible and simple PSR-11 dependency injection container

[![Build Status](https://drone.mnavarro.dev/api/badges/legatus/container/status.svg)](https://drone.mnavarro.dev/legatus/container)

## Installation
You can install the Container component using [Composer][composer]:

```bash
composer require legatus/container
```

## Quick Start

```php
<?php

use function Legatus\Support\Container\Definition\Argument\service;
use Legatus\Support\Container\LegatusContainer;
use Psr\Container\ContainerInterface as PsrContainer;

$container = new LegatusContainer();

// You can instantiate factories and fetch services from the passed container
$container->factory('some-service', static function (PsrContainer $c) {
    return new SomeService(
        $c->get('some-dependency-of-that-service')
    );
});

// Or, if you prefer, define everything properly
$container->register('some-service', SomeConcreteService::class)
    // Use the service function to inject another service as argument
    ->addArgument(service(SomeServiceNeededAsArgument::class))
    // Otherwise, arguments are treated as raw values
    ->addArgument('this-string-will-be-a-raw-argument');

// You can extend any defined service in Legatus container
$container->extend('some-service-to-extend')
    // You can decorate a service (this will override the service with a child type)
    ->decorate(static function (SomeInterface $service) use ($container) {
        return new DecoratedServiceWithCompatibleInterface(
            $container->get('other-service'),
            $service
        );
    })
    // Or you can inflect it to call methods on it, passing various arguments.
    // This will keep the reference unmodified
    ->inflect(static function (SomeService $service) {
        $service->someMethodInTheService();
    });

// You can add delegate containers
$container->addDelegate($someOtherContainer);
```

For more details you can check the [online documentation here][docs].

## Community
We still do not have a community channel. If you would like to help with that, you can let me know!

## Contributing
Read the contributing guide to know how can you contribute to Quilt.

## Security Issues
Please report security issues privately by email and give us a period of grace before disclosing.

## About Legatus
Legatus is a personal open source project led by Matías Navarro Carter and developed by contributors.

[composer]: https://getcomposer.org/
[docs]: https://legatus.mnavarro.dev/components/container