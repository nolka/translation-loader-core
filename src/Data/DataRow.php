<?php

declare(strict_types=1);

namespace TranslationLoader\Data;

/**
 * Class represents single element for translation
 * @package TranslationLoader\Data
 */
class DataRow
{
    /** @var string Source lang code */
    public $sourceLangCode;
    /** @var string Destination lang code */
    public $destLangCode;
    /** @var string Source value */
    public $sourceValue;
    /** @var string Translated value */
    public $destValue;
}
