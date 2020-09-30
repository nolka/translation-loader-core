<?php

declare(strict_types=1);


namespace TranslationLoader\Writer;

use TranslationLoader\Data\DataRow;

/**
 * Interface for writing translations
 * @package TranslationLoader\Writer
 */
interface TranslationWriterInterface
{
    /** @var string Field name in file with translations which is used to getting source text */
    const CELL_SOURCE_NAME = 'source';

    /**
     * Write translation to destination
     * @param DataRow $dataRow
     * @return bool
     */
    public function write(DataRow $dataRow): bool;

    /**
     * Finalize method. Used for finalizing translation writing process, disposing resources, etc.
     */
    public function finalize(): void;
}
