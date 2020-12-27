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

/**
 * Interface Tagger.
 */
interface Tagger
{
    /**
     * @param string $tagName     The tag name
     * @param string ...$services The services that will be bound to the tag
     */
    public function tag(string $tagName, string ...$services): void;
}
