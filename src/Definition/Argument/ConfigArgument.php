<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition\Argument;

use Legatus\Support\Container\Config\Reader;
use Psr\Container\ContainerInterface;

/**
 * Class ConfigArgument.
 */
class ConfigArgument implements Argument
{
    private string $path;
    /**
     * @var mixed|null
     */
    private $default;

    /**
     * ConfigArgument constructor.
     *
     * @param string $path
     * @param null   $default
     */
    public function __construct(string $path, $default = null)
    {
        $this->path = $path;
        $this->default = $default;
    }

    /**
     * @param ContainerInterface $container
     * @param Reader             $config
     *
     * @return mixed|void
     */
    public function resolve(ContainerInterface $container, Reader $config)
    {
        return $config->read($this->path, $this->default);
    }
}
