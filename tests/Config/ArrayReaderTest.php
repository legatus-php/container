<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support\Container\Tests\Config;

use Legatus\Support\Container\Config\ArrayReader;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayReaderTest.
 */
class ArrayReaderTest extends TestCase
{
    public function testItReadsEntryWithDotNotation(): void
    {
        $reader = new ArrayReader([
            'something' => [
                'very' => [
                    'deeply' => [
                        'nested' => 'OK',
                    ],
                ],
            ],
        ]);

        $this->assertSame('OK', $reader->read('something.very.deeply.nested'));
    }
}
