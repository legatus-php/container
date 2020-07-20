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
use ReflectionClass;
use ReflectionException;

/**
 * Class ReflectionContainer.
 */
final class ReflectionContainer implements ContainerInterface
{
    use ContainerArgumentReflectorResolver;

    /**
     * @var bool
     */
    private bool $cacheResolutions;
    /**
     * Cache of resolutions.
     *
     * @var array
     */
    private array $cache;

    /**
     * ReflectionContainer constructor.
     *
     * @param ContainerInterface $container
     * @param bool               $cacheResolutions
     */
    public function __construct(ContainerInterface $container, bool $cacheResolutions = true)
    {
        $this->container = $container;
        $this->cacheResolutions = $cacheResolutions;
        $this->cache = [];
    }

    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     */
    public function get($id)
    {
        if ($this->cacheResolutions === true && array_key_exists($id, $this->cache)) {
            return $this->cache[$id];
        }

        if (!$this->has($id)) {
            throw new NotFoundException(sprintf('Alias (%s) is not an existing class and therefore cannot be resolved', $id));
        }
        /** @psalm-suppress ArgumentTypeCoercion */
        $reflector = new ReflectionClass($id);
        $construct = $reflector->getConstructor();

        $resolution = $construct === null
            ? new $id()
            : $resolution = $reflector->newInstanceArgs($this->reflectArguments($construct))
        ;

        if ($this->cacheResolutions === true) {
            $this->cache[$id] = $resolution;
        }

        return $resolution;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return class_exists($id);
    }
}
