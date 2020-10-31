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
 * A Decorator is a function that returns a covariant type of the passed service.
 */
interface Decorator
{
    /**
     * @param object   $service
     * @param Services $s
     * @param Config   $c
     *
     * @return mixed The modified service
     */
    public function __invoke(object $service, Services $s, Config $c);
}
