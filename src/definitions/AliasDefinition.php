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
 * Class AliasDefinition.
 */
final class AliasDefinition extends BaseServiceDefinition
{
    private string $serviceName;

    /**
     * AliasDefinition constructor.
     *
     * @param string $alias
     * @param string $serviceName
     */
    public function __construct(string $alias, string $serviceName)
    {
        parent::__construct($alias);
        $this->serviceName = $serviceName;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed|void|null
     */
    public function resolve(ContainerInterface $container, Config $config)
    {
        // We skip caches
        return $this->doResolve($container, $config);
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed|void
     */
    protected function doResolve(ContainerInterface $container, Config $config)
    {
        return $container->get($this->serviceName);
    }
}
