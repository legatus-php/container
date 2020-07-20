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
use RuntimeException;

/**
 * Class DefinitionExtension.
 */
final class DeferredDefinition extends BaseServiceDefinition
{
    /**
     * @var callable
     */
    private $findExtension;

    /**
     * LazyExtension constructor.
     *
     * @param string   $id
     * @param callable $findExtension
     */
    public function __construct(string $id, callable $findExtension)
    {
        parent::__construct($id);
        $this->findExtension = $findExtension;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed|void
     */
    protected function doResolve(ContainerInterface $container, Config $config)
    {
        /** @var ServiceDefinition|null $definition */
        $definition = ($this->findExtension)($this->getId());
        if ($definition === null) {
            throw new RuntimeException(sprintf('Attempted to extend service "%s" but it was never declared', $this->getId()));
        }

        return $definition->resolve($container, $config);
    }
}
