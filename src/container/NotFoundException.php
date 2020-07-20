<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException.
 */
class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
    public static function lied(string $provider, string $id): self
    {
        return new self(sprintf('ServiceProvider "%s" lied about providing service of id "%s"', $provider, $id));
    }

    public static function id(string $id): self
    {
        return new self(sprintf('Service of id "%s" is not being managed in the container', $id));
    }
}
