<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition;

use InvalidArgumentException;
use Legatus\Support\Container\Config\Reader;
use Legatus\Support\Container\Definition\Argument\Argument;
use function Legatus\Support\Container\Definition\Argument\raw;
use Psr\Container\ContainerInterface;

/**
 * Class AbstractDefinition.
 */
abstract class AbstractDefinition implements Definition
{
    private string $id;
    /**
     * @var MethodCall[]
     */
    private array $methodCalls;
    /**
     * @var callable[]
     */
    private array $inflectors;
    /**
     * @var callable[]
     */
    private array $decorators;
    /**
     * @var bool
     */
    private bool $singleton;
    /**
     * @var mixed|null
     */
    private $cache;

    /**
     * AbstractDefinition constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->methodCalls = [];
        $this->inflectors = [];
        $this->decorators = [];
        $this->singleton = false;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCall(string $method, ...$args): Definition
    {
        foreach ($args as $key => $argument) {
            if (!$argument instanceof Argument) {
                $args[$key] = raw($argument);
            }
        }
        $this->methodCalls[] = new MethodCall($method, ...$args);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isSingleton(): bool
    {
        return $this->singleton;
    }

    /**
     * {@inheritdoc}
     */
    public function makeSingleton(): Definition
    {
        $this->singleton = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function makeMultiton(): Definition
    {
        $this->singleton = false;

        return $this;
    }

    public function inflect(callable $inflector): Definition
    {
        $this->inflectors[] = $inflector;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(callable $decorator): Definition
    {
        $this->decorators[] = $decorator;

        return $this;
    }

    public function resolve(ContainerInterface $container, Reader $config)
    {
        if ($this->cache !== null) {
            return $this->cache;
        }
        $instance = $this->doResolve($container, $config);
        foreach ($this->methodCalls as $methodCall) {
            $methodCall->call($instance, $container, $config);
        }
        foreach ($this->inflectors as $inflector) {
            $inflector($instance, $container, $config);
        }
        foreach ($this->decorators as $decorator) {
            $instance = $decorator($instance, $container, $config);
            if ($instance === null) {
                throw new InvalidArgumentException('Decorators must return the new decorated instance');
            }
        }
        if ($this->singleton === true) {
            $this->cache = $instance;
        }

        return $instance;
    }

    /**
     * @param ContainerInterface $container
     * @param Reader             $config
     *
     * @return mixed
     */
    abstract protected function doResolve(ContainerInterface $container, Reader $config);
}
