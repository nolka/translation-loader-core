<?php

declare(strict_types=1);


namespace TranslationLoader\Writer;

use TranslationLoader\TranslationManager;

/**
 * Base writer
 * @package TranslationLoader\Writer
 */
class BaseWriter
{
    protected TranslationManager $manager;

    /**
     * BaseWriter constructor.
     * @param TranslationManager $manager
     */
    public function __construct(TranslationManager $manager)
    {
        $this->manager = $manager;
    }
}
