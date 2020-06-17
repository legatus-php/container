<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Config;

/**
 * Class ArrayReader.
 *
 * Allows to query with dot notation on an array.
 */
class ArrayReader implements Reader
{
    protected static array $default = [
        'container' => [
            'autowire' => [
                'enabled' => false,
                'cache_resolutions' => true,
            ],
            'default_to_singleton' => true,
            'enable_bus' => true,
            'providers' => [],
        ],
    ];

    private array $config;

    /**
     * ArrayReader constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = array_merge_recursive(self::$default, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $query, $default = null)
    {
        $parts = explode('.', $query);
        $data = $this->config;
        while (count($parts) > 0) {
            if ($data === null) {
                return $default;
            }
            $part = array_shift($data);

            if (is_numeric($part)) {
                $part = (int) $part;
            }

            $data = $data[$part] ?? null;
        }

        return $data;
    }
}
