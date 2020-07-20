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
 * Class TagDefinition.
 */
final class TagDefinition extends BaseServiceDefinition
{
    /**
     * @var string[]
     */
    protected array $services;

    /**
     * TagExtension constructor.
     *
     * @param string      $tagName
     * @param string      $service
     * @param string|null $key
     */
    public function __construct(string $tagName, string $service, string $key = null)
    {
        parent::__construct($tagName);
        $this->services = [];
        $this->addService($service, $key);
    }

    /**
     * @param string     $service
     * @param string|int $key
     */
    public function addService(string $service, $key = null): void
    {
        $key = $key ?? count($this->services);
        if (!in_array($service, $this->services, true)) {
            $this->services[$key] = $service;
        }
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array
     */
    public function resolve(ContainerInterface $container, Config $config): array
    {
        // We override the method to bypass the cache.
        return $this->doResolve($container, $config);
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array
     */
    protected function doResolve(ContainerInterface $container, Config $config): array
    {
        $resolved = [];
        foreach ($this->services as $key => $service) {
            $resolved[$key] = $container->get($service);
        }

        return $resolved;
    }
}
