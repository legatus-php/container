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
 * Interface Extensible.
 */
interface Definition extends Resolvable
{
    /**
     * Adds a method call on the definition after has been created.
     *
     * @param string         $name
     * @param Argument|mixed ...$arguments
     *
     * @return Definition
     */
    public function addMethodCall(string $name, ...$arguments): Definition;

    /**
     * Decorates a reference.
     *
     * Please note that decorators override the original reference with the return
     * value of the callable.
     *
     * Use this when you want to decorate a reference.
     *
     * The callable MUST return a covariant of the service resolution to keep
     * Liskov Substitution.
     *
     * If you don't want to override the original reference, use the inflector.
     *
     * @param callable $decorator
     *
     * @return Definition
     */
    public function decorate(callable $decorator): Definition;

    /**
     * Inflects changes to a reference.
     *
     * Decorators act upon the passed reference, but this one is not overridden.
     *
     * Use this when you only have to modify internal state of the reference only.
     *
     * @param callable $inflector
     *
     * @return Definition
     */
    public function inflect(callable $inflector): Definition;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return bool
     */
    public function isSingleton(): bool;

    /**
     * @return Definition
     */
    public function makeSingleton(): Definition;

    /**
     * @return Definition
     */
    public function makeMultiton(): Definition;
}
