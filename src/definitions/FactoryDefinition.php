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
 * Class CallableDefinition.
 */
final class FactoryDefinition extends BaseServiceDefinition
{
    /**
     * @var callable
     */
    private $factory;

    /**
     * FactoryDefinition constructor.
     *
     * @param string   $id
     * @param callable $factory
     */
    public function __construct(string $id, callable $factory)
    {
        parent::__construct($id);
        $this->factory = $factory;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array|object
     */
    protected function doResolve(ContainerInterface $container, Config $config)
    {
        return ($this->factory)($container, $config);
    }
}
