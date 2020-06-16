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
 * Class CallableDefinition.
 */
final class FactoryDefinition extends AbstractDefinition
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
     *
     * @return array|object
     */
    protected function doResolve(ContainerInterface $container)
    {
        return ($this->factory)($container);
    }
}
