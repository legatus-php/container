<?php

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Psr\Container\ContainerInterface;

/**
 * Class LegatusContainerProvider.
 */
final class LegatusContainerProvider implements ServiceProvider
{
    public const REGISTER = 'legatus.container.register_as_service';
    public const REFLECTION_USE = 'legatus.container.reflection.use';
    public const REFLECTION_CACHE = 'legatus.container.reflection.cache';
    public const BUS_ENABLE = 'legatus.container.service_bus.enable';

    /**
     * @param Container $container
     * @param Config    $config
     */
    public function __invoke(Container $container, Config $config): void
    {
        if ($config(self::REGISTER) ?? true) {
            $container->register(ContainerInterface::class, fn () => $container);
        }
        if ($config(self::REFLECTION_USE) ?? false) {
            $container->addDelegate(ReflectionContainer::from($container, $config(self::REFLECTION_CACHE) ?? true));
        }
        if ($config(self::BUS_ENABLE)) {
            ServiceBus::configure($container);
        }
    }
}
