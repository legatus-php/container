<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Provider;

use Legatus\Support\Container\LegatusContainer;

/**
 * Interface ProviderFunction.
 *
 * A provider function is any callable that takes an instance of the Legatus
 * container as an argument.
 */
interface ProviderFunction
{
    /**
     * @param LegatusContainer $container
     */
    public function __invoke(LegatusContainer $container): void;
}
