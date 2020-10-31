<?php

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Psr\Container\ContainerInterface as Services;

/**
 * An Inflector is a function that modifies the internal state of a service by calling methods on it.
 */
interface Inflector
{
    /**
     * @param object   $service
     * @param Services $s
     * @param Config   $c
     */
    public function __invoke(object $service, Services $s, Config $c): void;
}
