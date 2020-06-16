<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Definition;

use Legatus\Support\Container\Definition\Argument\Argument;
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
     * @var Argument[]
     */
    private array $arguments;

    /**
     * MethodCall constructor.
     *
     * @param string     $methodName
     * @param Argument[] $arguments
     */
    public function __construct(string $methodName, Argument ...$arguments)
    {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    /**
     * @param object             $target
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function call(object $target, ContainerInterface $container)
    {
        if (!method_exists($target, $this->methodName)) {
            throw new \RuntimeException(sprintf('PaymentMethod %s does not exist in object of type %s', $this->methodName, get_class($target)));
        }

        return $target->{$this->methodName}(...$this->resolveArguments($container));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return array
     */
    protected function resolveArguments(ContainerInterface $container): array
    {
        $resolved = [];
        foreach ($this->arguments as $argument) {
            $resolved[] = $argument->resolve($container);
        }

        return $resolved;
    }
}
