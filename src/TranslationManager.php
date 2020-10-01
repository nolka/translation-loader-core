<?php

declare(strict_types=1);

namespace TranslationLoader;

use TranslationLoader\Data\DataRow;
use TranslationLoader\Reader\TranslationReaderInterface;
use TranslationLoader\Writer\TranslationWriterInterface;

/**
 * Менеджер переводов
 * @package App\Imports\Translations
 */
class TranslationManager
{
    const TRANSLATION_PREFIX = 'source';

    /** @var array keys are 2 char language code, value - language name */
    protected $langMap = [];

    public function __construct(array $langMap)
    {
        $this->langMap = $langMap;
    }

    /**
     * Выгрузить переводы
     * @param TranslationReaderInterface $reader
     * @param TranslationWriterInterface $writer
     * @return bool
     */
    public function copyTranslations(TranslationReaderInterface $reader, TranslationWriterInterface $writer): bool
    {
        foreach ($reader->read() as $dataRow) {
            /** @var $dataRow DataRow */
            $writer->write($dataRow);
        }
        $writer->finalize();
        return true;
    }

    /**
     * @return array
     */
    public function getLangMap(): array
    {
        return $this->langMap;
    }
}
