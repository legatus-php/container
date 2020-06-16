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

/**
 * Interface Definition.
 */
interface ArgumentDefinition extends Definition
{
    /**
     * @param Argument|mixed $argument
     *
     * @return $this
     */
    public function addArgument($argument): self;

    /**
     * @param int            $pos
     * @param Argument|mixed $argument
     *
     * @return $this
     */
    public function setArgument(int $pos, $argument): self;
}
