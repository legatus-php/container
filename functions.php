<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\ServiceBus;

/**
 * @param string $serviceId
 * @param string $method
 * @param mixed  ...$args
 *
 * @return mixed
 */
function call(string $serviceId, string $method, ...$args)
{
    return Bus::call($serviceId, $method, ...$args);
}

namespace Legatus\Support\Container\Definition\Argument;

/**
 * @param $value
 *
 * @return RawArgument
 */
function raw($value): RawArgument
{
    return new RawArgument($value);
}

/**
 * @param string $id
 *
 * @return ServiceArgument
 */
function service(string $id): ServiceArgument
{
    return new ServiceArgument($id);
}
