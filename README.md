Legatus Container
=================

Flexible and simple PSR-11 dependency injection container

[![Type Coverage](https://shepherd.dev/github/legatus-php/container/coverage.svg)](https://shepherd.dev/github/legatus-php/container)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Flegatus-php%2Fcontainer%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/legatus-php/container/master)

## Installation
You can install the Container component using [Composer][composer]:

```bash
composer require legatus/container
```

## Quick Start

```php
<?php

use function Legatus\Support\service;
use Legatus\Support\Container;
use Psr\Container\ContainerInterface as PsrContainer;

$container = new Container();

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

# Project status & release process

While this library is still under development, it is well tested and should be stable enough to use in production environments.

The current releases are numbered 0.x.y. When a non-breaking change is introduced (adding new methods, optimizing existing code, etc.), y is incremented.

When a breaking change is introduced, a new 0.x version cycle is always started.

It is therefore safe to lock your project to a given release cycle, such as 0.2.*.

If you need to upgrade to a newer release cycle, check the [release history][releases] for a list of changes introduced by each further 0.x.0 version.

## Community
We still do not have a community channel. If you would like to help with that, you can let me know!

## Contributing
Read the contributing guide to know how can you contribute to Legatus.

## Security Issues
Please report security issues privately by email and give us a period of grace before disclosing.

## About Legatus
Legatus is a personal open source project led by Matías Navarro Carter and developed by contributors.

[composer]: https://getcomposer.org/
[docs]: https://legatus.dev/components/container
[releases]: https://github.com/legatus-php/container/releases