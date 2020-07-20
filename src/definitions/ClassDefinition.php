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
 * Class ConcreteDefinition.
 */
final class ClassDefinition extends BaseServiceDefinition implements ArgumentServiceDefinition
{
    /**
     * @var Resolvable[]
     */
    protected array $arguments;
    /**
     * @var string
     */
    private string $className;

    /**
     * BaseServiceDefinition constructor.
     *
     * @param string $id
     * @param string $className
     */
    public function __construct(string $id, string $className)
    {
        parent::__construct($id);
        $this->arguments = [];
        $this->className = $className;
        $this->guard();
    }

    /**
     * {@inheritdoc}
     */
    public function addArgument($argument): ArgumentServiceDefinition
    {
        if (!$argument instanceof Resolvable) {
            $argument = raw($argument);
        }
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setArgument(int $pos, $argument): ArgumentServiceDefinition
    {
        if (!$argument instanceof Resolvable) {
            $argument = raw($argument);
        }
        $this->arguments[$pos] = $argument;

        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array|object|void
     */
    protected function doResolve(ContainerInterface $container, Config $config)
    {
        return new $this->className(...$this->resolveArguments($container, $config));
    }

    protected function guard(): void
    {
        if (!class_exists($this->className)) {
            throw new RuntimeException(sprintf('Class %s does not exists', $this->className));
        }
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array
     */
    protected function resolveArguments(ContainerInterface $container, Config $config): array
    {
        $resolved = [];
        foreach ($this->arguments as $argument) {
            $resolved[] = $argument->resolve($container, $config);
        }

        return $resolved;
    }
}
