<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition\Argument;

use Psr\Container\ContainerInterface;

/**
 * Class RawArgument.
 */
class RawArgument implements Argument
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * RawArgument constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function resolve(ContainerInterface $container)
    {
        return $this->value;
    }
}
