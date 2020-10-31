<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Psr\Container\ContainerInterface as PsrContainer;

$container = new Legatus\Support\Container();

// You can instantiate factories and fetch services from the passed container
$container->register('some-service', static function (PsrContainer $c) {
    return new SomeService(
        $c->get('some-dependency-of-that-service')
    );
});
