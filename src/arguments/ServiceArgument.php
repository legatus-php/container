<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Psr\Container\ContainerInterface;

/**
 * Class ServiceArgument.
 */
class ServiceArgument implements Resolvable
{
    private string $serviceName;
    private ?MethodCall $methodCall;

    /**
     * ServiceArgument constructor.
     *
     * @param string $serviceName
     */
    public function __construct(string $serviceName)
    {
        $this->serviceName = $serviceName;
        $this->methodCall = null;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed
     */
    public function resolve(ContainerInterface $container, Config $config)
    {
        $service = $container->get($this->serviceName);
        if ($this->methodCall === null) {
            return $service;
        }

        return $this->methodCall->call($service, $container, $config);
    }

    /**
     * @param string     $method
     * @param Resolvable ...$arguments
     *
     * @return $this
     */
    public function call(string $method, Resolvable ...$arguments): self
    {
        $this->methodCall = new MethodCall($method, ...$arguments);

        return $this;
    }
}
