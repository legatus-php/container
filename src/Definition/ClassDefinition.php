<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition;

use Legatus\Support\Container\Definition\Argument\Argument;
use function Legatus\Support\Container\Definition\Argument\raw;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Class ConcreteDefinition.
 */
final class ClassDefinition extends AbstractDefinition implements ArgumentDefinition
{
    /**
     * @var Argument[]
     */
    protected array $arguments;
    /**
     * @var string
     */
    private string $className;

    /**
     * AbstractDefinition constructor.
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
    public function addArgument($argument): ArgumentDefinition
    {
        if (!$argument instanceof Argument) {
            $argument = raw($argument);
        }
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setArgument(int $pos, $argument): ArgumentDefinition
    {
        if (!$argument instanceof Argument) {
            $argument = raw($argument);
        }
        $this->arguments[$pos] = $argument;

        return $this;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return array|object|void
     */
    protected function doResolve(ContainerInterface $container)
    {
        return new $this->className(...$this->resolveArguments($container));
    }

    protected function guard(): void
    {
        if (!class_exists($this->className)) {
            throw new RuntimeException(sprintf('Class %s does not exists', $this->className));
        }
    }

    /**
     * @param ContainerInterface $container
     *
     * @return array
     */
    protected function resolveArguments(ContainerInterface $container): array
    {
        $resolved = [];
        foreach ($this->arguments as $argument) {
            $resolved[] = $argument->resolve($container);
        }

        return $resolved;
    }
}
