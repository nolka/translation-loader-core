<?php

declare(strict_types=1);

namespace TranslationLoader\Reader;

use TranslationLoader\Data\DataRow;

/**
 * Used for creating translations from cli, or app controller, or service, etc...
 * @package TranslationLoader\Reader
 */
class UserInputReader implements TranslationReaderInterface
{
    /** @var DataRow Datarow for translation */
    protected $dataRow;

    /**
     * HardcodedReader constructor.
     * @param string $sourceLangCode
     * @param string $sourceValue
     * @param string $destLangCode
     * @param string $destValue
     */
    public function __construct(string $sourceLangCode, string $sourceValue, string $destLangCode, string $destValue)
    {
        $this->dataRow = new DataRow();
        $this->dataRow->sourceLangCode = $sourceLangCode;
        $this->dataRow->sourceValue = $sourceValue;
        $this->dataRow->destLangCode = $destLangCode;
        $this->dataRow->destValue = $destValue;
    }

    /**
     * @inheritDoc
     */
    public function read(): \Generator
    {
        yield $this->dataRow;
    }
}
