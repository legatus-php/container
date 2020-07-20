<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

/**
 * Interface ServiceProvider.
 *
 * A Service Provider is an object that provides services to the main Legatus
 * Container instance.
 */
interface ServiceProvider
{
    /**
     * @param Container $container
     * @param Config    $config
     */
    public function register(Container $container, Config $config): void;
}
