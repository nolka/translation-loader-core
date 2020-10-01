<?php

declare(strict_types=1);

namespace TranslationLoader\Reader;

use TranslationLoader\TranslationManager;

/**
 * Base reader class
 * @package TranslationLoader\Reader
 */
class BaseReader
{
    /** @var TranslationManager */
    protected $manager;

    /**
     * BaseReader constructor.
     * @param TranslationManager $manager
     */
    public function __construct(TranslationManager $manager)
    {
        $this->manager = $manager;
    }
}
