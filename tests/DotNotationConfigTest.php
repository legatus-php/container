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
