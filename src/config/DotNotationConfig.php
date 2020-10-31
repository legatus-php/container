<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

/**
 * DotNotationConfig reads configuration entries from a multidimensional array
 * in a dot notation fashion.
 */
final class DotNotationConfig implements Config
{
    private array $config;

    /**
     * DotNotationConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(string $entry)
    {
        $parts = explode('.', $entry);
        $data = $this->config;
        while (count($parts) > 0) {
            if ($data === null) {
                return null;
            }
            $part = array_shift($parts);

            if (is_numeric($part)) {
                $part = (int) $part;
            }

            $data = $data[$part] ?? null;
        }

        return $data;
    }
}
