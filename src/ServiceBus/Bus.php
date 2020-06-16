<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\ServiceBus;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Class Bus.
 */
class Bus
{
    /**
     * @var ContainerInterface|null
     */
    private static ?ContainerInterface $container = null;

    /**
     * @param ContainerInterface $container
     */
    public static function configure(ContainerInterface $container): void
    {
        if (self::$container === null) {
            self::$container = $container;
        }
    }

    /**
     * @param string $serviceId
     * @param string $method
     * @param mixed  ...$args
     *
     * @return mixed
     */
    public static function call(string $serviceId, string $method, ...$args)
    {
        if (self::$container === null) {
            throw new RuntimeException('The service bus has not been configured');
        }
        if (!self::$container->has($serviceId)) {
            throw new InvalidArgumentException('Service container does not contain %s service');
        }
        $instance = self::$container->get($serviceId);

        if (!method_exists($instance, $method)) {
            throw new InvalidArgumentException(sprintf('Method "%s" does not exists in "%s"', $method, get_class($instance)));
        }

        return $instance->{$method}(...$args);
    }
}
