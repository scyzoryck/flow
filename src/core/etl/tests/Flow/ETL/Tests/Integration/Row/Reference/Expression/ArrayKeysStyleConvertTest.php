<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Integration\Row\Reference\Expression;

use function Flow\ETL\DSL\array_keys_style_convert;
use function Flow\ETL\DSL\ref;
use Flow\ETL\DSL\From;
use Flow\ETL\DSL\To;
use Flow\ETL\Flow;
use Flow\ETL\Memory\ArrayMemory;
use PHPUnit\Framework\TestCase;

final class ArrayKeysStyleConvertTest extends TestCase
{
    public function test_array_keys_style_convert() : void
    {
        (new Flow())
            ->read(
                From::array(
                    [
                        ['id' => 1, 'array' => ['camelCased' => 1, 'snake_cased' => 2, 'space word' => 3]],
                    ]
                )
            )
            ->withEntry('array', array_keys_style_convert(ref('array'), 'kebab'))
            ->write(To::memory($memory = new ArrayMemory()))
            ->run();

        $this->assertSame(
            [
                ['id' => 1, 'array' => ['camel-cased' => 1, 'snake-cased' => 2, 'space-word' => 3]],
            ],
            $memory->data
        );
    }
}
