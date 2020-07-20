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
 * Class NullReader.
 */
class NullConfig implements Config
{
    /**
     * {@inheritdoc}
     */
    public function read(string $query, $default = null)
    {
        return $default;
    }
}
