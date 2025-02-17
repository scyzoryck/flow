<?php

declare(strict_types=1);

namespace Flow\ETL\DSL;

use Flow\ETL\Adapter\Avro\FlixTech\AvroExtractor;
use Flow\ETL\Adapter\Avro\FlixTech\AvroLoader;
use Flow\ETL\Extractor;
use Flow\ETL\Filesystem\Path;
use Flow\ETL\Loader;
use Flow\ETL\Row\Schema;

/**
 * @infection-ignore-all
 */
class Avro
{
    /**
     * @param array<Path|string>|Path|string $path
     */
    final public static function from(
        Path|string|array $path,
        int $rows_in_batch = 1000,
    ) : Extractor {
        if (\is_array($path)) {
            /** @var array<Extractor> $extractors */
            $extractors = [];

            foreach ($path as $next_path) {
                $extractors[] = new AvroExtractor(
                    \is_string($next_path) ? Path::realpath($next_path) : $next_path,
                    $rows_in_batch
                );
            }

            return new Extractor\ChainExtractor(...$extractors);
        }

        return new AvroExtractor(
            \is_string($path) ? Path::realpath($path) : $path,
            $rows_in_batch
        );
    }

    /**
     * @param Path|string $path
     * @param null|Schema $schema
     *
     * @return Loader
     */
    final public static function to(Path|string $path, Schema $schema = null) : Loader
    {
        return new AvroLoader(
            \is_string($path) ? Path::realpath($path) : $path,
            $schema
        );
    }
}
