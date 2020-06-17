<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Provider;

use Legatus\Support\Container\Config\Reader;
use Legatus\Support\Container\LegatusContainer;

/**
 * Interface ServiceProvider.
 *
 * A Service Provider is an object that provides services to the main Legatus
 * container instance.
 */
interface ServiceProvider
{
    /**
     * @param LegatusContainer $container
     * @param Reader           $config
     */
    public function register(LegatusContainer $container, Reader $config): void;
}
