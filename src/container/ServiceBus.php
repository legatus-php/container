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

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Class ServiceBus.
 *
 * This class wraps a container interface and allows it to call methods on
 * different services statically (globally) through the ServiceBus::call() method.
 *
 * Using this is a bad idea if your services are not designed properly: i.e.
 * they contain shared state. Ideally, the methods you call should not have
 * side effects and must have some sort of deterministic output based on the input.
 *
 * It is also not a good idea to use this method everywhere. It's okay in
 * controllers for some minor http related stuff, for example. But please do not
 * use this in your domain layer. Bear in mind that using this bounds your code
 * to this specific container library and also makes your classes harder to test.
 *
 * As with everything, balance it out and use it wisely.
 */
class ServiceBus
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
