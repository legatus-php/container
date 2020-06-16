<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container;

use Psr\Container\ContainerInterface;
use ReflectionFunctionAbstract;
use ReflectionParameter;

/**
 * Trait ContainerArgumentReflectorResolver.
 */
trait ContainerArgumentReflectorResolver
{
    private ContainerInterface $container;

    /**
     * @param ReflectionFunctionAbstract $method
     *
     * @psalm-suppress PossiblyNullReference
     *
     * @return array
     */
    protected function reflectArguments(ReflectionFunctionAbstract $method): array
    {
        return array_map(function (ReflectionParameter $param) use ($method) {
            $name = $param->getName();
            $typeName = $param->getType() !== null ? $param->getType()->getName() : null;

            // If argument is typed and is not built-in
            if ($typeName !== null && !$param->getType()->isBuiltin()) {
                // And is in internal container, we resolve it
                if ($this->container && $this->container->has($typeName)) {
                    return $this->container->get($typeName);
                }
                // Otherwise, we attempt reflection on it
                return $this->get($typeName);
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            if ($param->isOptional() !== null) {
                return null;
            }

            throw new NotFoundException(sprintf('Unable to resolve a value for parameter (%s) in the function/method (%s)', $name, $method->getName()));
        }, $method->getParameters());
    }
}
