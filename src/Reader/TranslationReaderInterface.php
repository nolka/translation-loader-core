<?php

namespace TranslationLoader\Reader;

use Generator;

/**
 * Interface for reading translations
 * @package translation_\Reader
 */
interface TranslationReaderInterface
{
    /** @var string Field name in file with translations */
    const CELL_SOURCE_NAME = 'source';

    /**
     * Returns generator that yields DataRow instance
     * @return Generator
     */
    public function read(): Generator;
}
