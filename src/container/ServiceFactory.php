<?php

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Psr\Container\ContainerInterface;

/**
 * A Service Factory is a function that creates a service.
 */
interface ServiceFactory
{
    /**
     * @param ContainerInterface $s
     * @param Config             $c
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $s, Config $c);
}
