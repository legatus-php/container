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
use UltraLite\CompositeContainer\CompositeContainer;

/**
 * Class Container.
 */
class Container implements ContainerInterface, DefinitionHelper
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Definition[]
     */
    protected array $definitions;
    /**
     * @var string[][]
     */
    protected array $tags;
    /**
     * @var CompositeContainer
     */
    protected CompositeContainer $delegates;

    /**
     * Container constructor.
     *
     * @param Config|null $config
     */
    public function __construct(Config $config = null)
    {
        $this->config = $config ?? new NullConfig();
        $this->definitions = [];
        $this->tags = [];
        $this->delegates = new CompositeContainer();
    }

    /**
     * @param ContainerInterface $container
     */
    public function addDelegate(ContainerInterface $container): void
    {
        $this->delegates->addContainer($container);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get($id)
    {
        if ($this->hasDefinition($id)) {
            return $this->fetch($id)->resolve($this, $this->config);
        }
        if ($this->hasTag($id)) {
            return array_map(fn (Definition $definition) => $definition->resolve($this, $this->config), $this->fetchTag($id));
        }
        if ($this->delegates->has($id)) {
            return $this->delegates->get($id);
        }
        throw new NotFoundException(sprintf('Service "%s" not found', $id));
    }

    /**
     * @param string $id
     * @noinspection PhpMissingParamTypeInspection
     *
     * @return bool
     */
    public function has($id): bool
    {
        return $this->hasDefinition($id) || $this->hasTag($id) || $this->delegates->has($id);
    }

    /**
     * @param callable|ServiceProvider $serviceProvider
     */
    public function provide(callable $serviceProvider): void
    {
        $serviceProvider($this, $this->config);
    }

    /**
     * @param string                      $abstract
     * @param string|callable|object|null $concrete
     *
     * @return Definition
     */
    public function register(string $abstract, $concrete = null): Definition
    {
        $definition = $this->getOrCreateDefinition($abstract);
        if (is_object($concrete) && !$concrete instanceof \Closure) {
            $concrete = static fn () => $concrete;
        }
        if (is_string($concrete) && class_exists($concrete)) {
            $concrete = fn () => $this->delegates->get($concrete);
        }
        if ($concrete === null && class_exists($abstract)) {
            $concrete = fn () => $this->delegates->get($abstract);
        }
        if (is_callable($concrete)) {
            $definition->setFactory($concrete);
        }
        $this->definitions[$abstract] = $definition;

        return $definition;
    }

    /**
     * @param string             $id
     * @param callable|Inflector $inflector
     */
    public function inflect(string $id, callable $inflector): void
    {
        $definition = $this->getOrCreateDefinition($id);
        $definition->inflect($inflector);
        $this->definitions[$id] = $definition;
    }

    public function decorate(string $id, callable $decorator): void
    {
        $definition = $this->getOrCreateDefinition($id);
        $definition->decorate($decorator);
        $this->definitions[$id] = $definition;
    }

    public function alias(string $id, string $target): void
    {
        $definition = $this->getOrCreateDefinition($target);
        $this->definitions[$id] = $definition;
    }

    /**
     * @param string $tagName
     * @param string ...$services
     */
    public function tag(string $tagName, string ...$services): void
    {
        if (!array_key_exists($tagName, $this->tags)) {
            $this->tags[$tagName] = [];
        }
        foreach ($services as $service) {
            if (in_array($service, $this->tags[$tagName], true)) {
                throw new \RuntimeException(sprintf('Service "%s" is already in tag "%s"', $service, $tagName));
            }
            $this->tags[$tagName][] = $service;
        }
    }

    private function getOrCreateDefinition(string $id): Definition
    {
        return $this->definitions[$id] ?? new Definition($id, $this);
    }

    /**
     * Fetches a single definition from the container.
     */
    private function fetch(string $id): Definition
    {
        if (!$this->hasDefinition($id)) {
            throw new \RuntimeException(sprintf('Service of id "%s" not found', $id));
        }

        return $this->definitions[$id];
    }

    /**
     * @param string $tagName
     *
     * @return Definition[]
     */
    private function fetchTag(string $tagName): array
    {
        $definitions = [];
        $ids = $this->tags[$tagName] ?? [];
        foreach ($ids as $id) {
            $definitions[] = $this->fetch($id);
        }

        return $definitions;
    }

    private function hasDefinition(string $id): bool
    {
        return array_key_exists($id, $this->definitions) && $this->definitions[$id]->hasFactory();
    }

    private function hasTag(string $id): bool
    {
        return array_key_exists($id, $this->tags);
    }
}
