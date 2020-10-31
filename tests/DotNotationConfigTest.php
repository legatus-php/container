<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use PHPUnit\Framework\TestCase;

/**
 * Class DotNotationConfigTest.
 */
class DotNotationConfigTest extends TestCase
{
    public function testItReadsEntryWithDotNotation(): void
    {
        $config = new DotNotationConfig([
            'something' => [
                'very' => [
                    'deeply' => [
                        'nested' => 'OK',
                    ],
                ],
            ],
        ]);

        self::assertSame('OK', $config('something.very.deeply.nested'));
    }
}
