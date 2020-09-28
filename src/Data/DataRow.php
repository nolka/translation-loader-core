<?php

namespace TranslationLoader\Data;

/**
 * Class represents single element for translation
 * @package TranslationLoader\Data
 */
class DataRow
{
    /** @var string Source lang code */
    public string $sourceLangCode;
    /** @var string Destination lang code */
    public string $destLangCode;
    /** @var string Source value */
    public string $sourceValue;
    /** @var string Translated value */
    public string $destValue;
}
