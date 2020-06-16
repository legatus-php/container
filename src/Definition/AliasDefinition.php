<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition;

use Psr\Container\ContainerInterface;

/**
 * Class AliasDefinition.
 */
final class AliasDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    private $serviceName;

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
     *
     * @return mixed|void|null
     */
    public function resolve(ContainerInterface $container)
    {
        // We skip caches
        return $this->doResolve($container);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return mixed|void
     */
    protected function doResolve(ContainerInterface $container)
    {
        return $container->get($this->serviceName);
    }
}
