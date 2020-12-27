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

use Psr\Container\ContainerInterface;

/**
 * A Service Factory is a function that creates a service.
 */
interface ServiceFactory
{
    /**
     * @param ContainerInterface $s
     * @param Config             $c
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $s, Config $c);
}
