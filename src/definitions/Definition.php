<?php

declare(strict_types=1);

/*
 * @project Legatus Container
 * @link https://github.com/legatus-php/container
 * @package legatus/container
 * @author Matias Navarro-Carter mnavarrocarter@gmail.com
 * @license MIT
 * @copyright 2021 Matias Navarro-Carter
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Psr\Container\ContainerInterface;

/**
 * Class Definition.
 */
class Definition
{
    private string $name;
    /**
     * @var callable|ServiceFactory|null
     */
    private $factory;
    private bool $singleton;
    /**
     * @var mixed|null
     */
    private $cache;
    /**
     * @var Inflector[]|callable[]
     */
    private array $inflectors;
    /**
     * @var Decorator[]|callable[]
     */
    private array $decorators;

    private DefinitionHelper $helper;

    /**
     * Definition constructor.
     *
     * @param string           $name
     * @param DefinitionHelper $helper
     */
    public function __construct(string $name, DefinitionHelper $helper)
    {
        $this->name = $name;
        $this->helper = $helper;
        $this->singleton = true;
        $this->inflectors = [];
        $this->decorators = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param callable $factory
     *
     * @return $this
     */
    public function setFactory(callable $factory): Definition
    {
        if ($this->factory !== null) {
            throw new \RuntimeException(sprintf('Factory for service "%s" has already been registered', $this->name));
        }
        $this->factory = $factory;

        return $this;
    }

    public function hasFactory(): bool
    {
        return $this->factory !== null;
    }

    /**
     * @param callable $inflector
     *
     * @return $this
     */
    public function inflect(callable $inflector): Definition
    {
        $this->ensureIsNotResolvedAlready();
        $this->inflectors[] = $inflector;

        return $this;
    }

    /**
     * @param callable $decorator
     *
     * @return $this
     */
    public function decorate(callable $decorator): Definition
    {
        $this->ensureIsNotResolvedAlready();
        $this->decorators[] = $decorator;

        return $this;
    }

    /**
     * @param string ...$tags
     *
     * @return $this
     */
    public function tag(string ...$tags): Definition
    {
        foreach ($tags as $tag) {
            $this->helper->tag($tag, $this->name);
        }

        return $this;
    }

    public function alias(string ...$aliases): Definition
    {
        foreach ($aliases as $alias) {
            $this->helper->alias($alias, $this->name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function multiton(): Definition
    {
        $this->singleton = false;

        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed|null
     */
    public function resolve(ContainerInterface $container, Config $config)
    {
        if ($this->singleton === true && $this->cache !== null) {
            return $this->cache;
        }

        if ($this->factory === null) {
            // TODO: Proper exception
            throw new \RuntimeException('Unresolvable service');
        }

        $resolved = ($this->factory)($container, $config);

        // Run inflectors
        foreach ($this->inflectors as $inflector) {
            $inflector($resolved, $container, $config);
        }

        // Run decorators
        foreach ($this->decorators as $decorator) {
            $resolved = $decorator($resolved, $container, $config);
        }

        // Cache resolution always
        $this->cache = $resolved;

        return $resolved;
    }

    private function ensureIsNotResolvedAlready(): void
    {
        if ($this->cache !== null) {
            throw new \RuntimeException(sprintf('Cannot modify "%s" service because it has already been resolved', $this->name));
        }
    }
}
