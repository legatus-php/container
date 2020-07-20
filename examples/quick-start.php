<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

use function Legatus\Support\service;
use Psr\Container\ContainerInterface as PsrContainer;

$container = new Legatus\Support\Container();

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
