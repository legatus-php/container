<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition;

use Legatus\Support\Container\Config\Reader;
use Psr\Container\ContainerInterface;

/**
 * Interface Resolvable.
 */
interface Resolvable
{
    /**
     * @param ContainerInterface $container
     * @param Reader             $config
     *
     * @return mixed
     */
    public function resolve(ContainerInterface $container, Reader $config);
}
