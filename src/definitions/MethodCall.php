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

/**
 * Class MethodCall.
 *
 * @internal
 */
class MethodCall
{
    private string $methodName;
    /**
     * @var Resolvable[]
     */
    private array $arguments;

    /**
     * MethodCall constructor.
     *
     * @param string     $methodName
     * @param Resolvable ...$arguments
     */
    public function __construct(string $methodName, Resolvable ...$arguments)
    {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    /**
     * @param object             $target
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return mixed
     */
    public function call(object $target, ContainerInterface $container, Config $config)
    {
        if (!method_exists($target, $this->methodName)) {
            throw new \RuntimeException(sprintf('PaymentMethod %s does not exist in object of type %s', $this->methodName, get_class($target)));
        }

        return $target->{$this->methodName}(...$this->resolveArguments($container, $config));
    }

    /**
     * @param ContainerInterface $container
     * @param Config             $config
     *
     * @return array
     */
    protected function resolveArguments(ContainerInterface $container, Config $config): array
    {
        $resolved = [];
        foreach ($this->arguments as $argument) {
            $resolved[] = $argument->resolve($container, $config);
        }

        return $resolved;
    }
}
