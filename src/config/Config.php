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
 * Interface Config.
 *
 * Reads configuration from any source.
 */
interface Config
{
    /**
     * Reads a configuration entry.
     *
     * @param string $entry
     *
     * @return mixed|null
     */
    public function __invoke(string $entry);
}
