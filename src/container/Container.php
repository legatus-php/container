<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Closure;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Class Container.
 */
class Container implements ContainerInterface
{
    private Config $config;
    /**
     * Delegate containers are used when services are impossible to resolve.
     *
     * @var ContainerInterface[]
     */
    private array $delegates;
    /**
     * Completed definitions are definitions that can be instantiated.
     *
     * @var ServiceDefinition[]
     */
    private array $completed;
    /**
     * Deferred definitions are definitions created by the extend method.
     * A definition does not need to exist in order to be extended. The definition
     * being extended will be created at resolve-time.
     *
     * @var DeferredDefinition[]
     */
    private array $deferred;
    /**
     * @var Closure[]|ServiceProvider[]
     */
    private array $providers;

    /**
     * @param array $config
     *
     * @return ContainerInterface
     */
    public static function configure(array $config): ContainerInterface
    {
        return new self(new ArrayConfig($config));
    }

    /**
     * Container constructor.
     *
     * @param Config|null $config
     */
    public function __construct(Config $config = null)
    {
        $this->config = $config ?? new NullConfig();
        $this->delegates = [];
        $this->completed = [];
        $this->deferred = [];
        $this->providers = [];
    }

    /**
     * @param string $id
     *
     * @return mixed|object
     */
    public function get($id)
    {
        if ($id === ContainerInterface::class || $id === __CLASS__) {
            return $this;
        }

        // First, we check if is in a deferred definition.
        if (array_key_exists($id, $this->deferred)) {
            return $this->deferred[$id]->resolve($this, $this->config);
        }
        // Then, we check if is in a completed service
        if (array_key_exists($id, $this->completed)) {
            return $this->completed[$id]->resolve($this, $this->config);
        }
        // Then in the container delegates
        foreach ($this->delegates as $container) {
            if ($container->has($id)) {
                return $container->get($id);
            }
        }
        throw NotFoundException::id($id);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        if ($id === ContainerInterface::class || $id === __CLASS__) {
            return true;
        }
        if (array_key_exists($id, $this->deferred)) {
            return true;
        }
        if (array_key_exists($id, $this->completed)) {
            return true;
        }
        foreach ($this->delegates as $container) {
            if ($container->has($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ServiceProvider $provider
     * @param string|null     $name
     */
    public function addProvider(ServiceProvider $provider, string $name = null): void
    {
        $name = $name ?? get_class($provider);
        if (array_key_exists($name, $this->providers)) {
            throw new RuntimeException(sprintf('Provided named %s is already registered', $name));
        }
        $provider->register($this, $this->config);
        $this->providers[$name] = $provider;
    }

    /**
     * @param ContainerInterface $container
     */
    public function addDelegate(ContainerInterface $container): void
    {
        $this->delegates[] = $container;
    }

    /**
     * @param string $id
     *
     * @return ServiceDefinition
     */
    public function extend(string $id): ServiceDefinition
    {
        if (!array_key_exists($id, $this->deferred)) {
            $definition = new DeferredDefinition(
                $id,
                Closure::fromCallable([$this, 'findCompleted'])
            );
            $this->makeSingletonIfApplies($definition);
            $this->deferred[$id] = $definition;
        }

        return $this->deferred[$id];
    }

    /**
     * @param string      $id
     * @param string|null $concrete
     *
     * @return ArgumentServiceDefinition
     */
    public function register(string $id, string $concrete = null): ArgumentServiceDefinition
    {
        $this->ensureDefinitionDoesNotExist($id);
        $concrete = $concrete ?? $id;
        $definition = new ClassDefinition(
            $id,
            $concrete
        );
        $this->makeSingletonIfApplies($definition);
        $this->completed[$id] = $definition;

        return $definition;
    }

    /**
     * @param string   $id
     * @param callable $factory
     *
     * @return ServiceDefinition
     */
    public function factory(string $id, callable $factory): ServiceDefinition
    {
        $this->ensureDefinitionDoesNotExist($id);
        $definition = new FactoryDefinition(
            $id,
            $factory
        );
        $this->makeSingletonIfApplies($definition);
        $this->completed[$id] = $definition;

        return $definition;
    }

    /**
     * @param string $alias
     * @param string $id
     */
    public function alias(string $alias, string $id): void
    {
        if (array_key_exists($alias, $this->completed)) {
            throw new RuntimeException(sprintf('There is already a definition using the id "%s"', $alias));
        }
        $this->completed[$alias] = new AliasDefinition($alias, $id);
    }

    /**
     * @param string $tagName
     * @param string $id
     */
    public function tag(string $tagName, string $id): void
    {
        $key = null;
        if (strpos($tagName, '#') !== false) {
            [$tagName, $key] = explode('#', $tagName);
        }
        if (!array_key_exists($tagName, $this->completed)) {
            $this->completed[$tagName] = new TagDefinition($tagName, $id, $key);

            return;
        }
        $definition = $this->completed[$tagName] ?? null;
        if (!$definition instanceof TagDefinition) {
            throw new RuntimeException('Trying to override a definition id that is not a tag');
        }
        $definition->addService($id, $key);
    }

    /**
     * @param string $id
     */
    protected function ensureDefinitionDoesNotExist(string $id): void
    {
        if (array_key_exists($id, $this->completed)) {
            throw new RuntimeException(sprintf('ServiceDefinition with id "%s" already exists', $id));
        }
    }

    /**
     * @param string $id
     *
     * @return ServiceDefinition|null
     */
    protected function findCompleted(string $id): ?ServiceDefinition
    {
        if (!array_key_exists($id, $this->completed)) {
            return null;
        }

        return $this->completed[$id];
    }

    /**
     * @param ServiceDefinition $definition
     */
    private function makeSingletonIfApplies(ServiceDefinition $definition): void
    {
        if ($this->config->read('container.default_to_singleton', true) === true) {
            $definition->makeSingleton();
        }
    }
}
