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
    /** @var TranslationManager $manager */
    protected $manager;

    /**
     * BaseWriter constructor.
     * @param TranslationManager $manager
     */
    public function __construct(TranslationManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function finalize(): void
    {
        // Not implemented
    }
}
