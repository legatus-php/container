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
use ReflectionException;
use Yiisoft\Injector\Injector;

/**
 * Class ReflectionContainer.
 */
final class ReflectionContainer implements ContainerInterface
{
    private Injector $injector;
    private bool $cacheResolutions;
    /**
     * Cache of resolutions.
     *
     * @var array
     */
    private array $cache;

    /**
     * @param ContainerInterface $container
     * @param bool               $cacheResolutions
     *
     * @return ReflectionContainer
     */
    public static function from(ContainerInterface $container, bool $cacheResolutions = true): ReflectionContainer
    {
        return new self(new Injector($container), $cacheResolutions);
    }

    /**
     * ReflectionContainer constructor.
     *
     * @param Injector $injector
     * @param bool     $cacheResolutions
     */
    public function __construct(Injector $injector, bool $cacheResolutions = true)
    {
        $this->injector = $injector;
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

        $service = $this->injector->make($id);

        if ($this->cacheResolutions === true) {
            $this->cache[$id] = $service;
        }

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return class_exists($id);
    }
}
