<?php

namespace TranslationLoader\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use TranslationLoader\Data\DataRow;
use TranslationLoader\TranslationManager;

/**
 * Used for writing translations in xlsx file
 * @package TranslationLoader\Writer
 */
class XlsxWriter extends BaseWriter implements TranslationWriterInterface
{
    protected string $pathToFile;

    protected IWriter $writer;
    protected Spreadsheet $sheet;

    /** @var array */
    protected $languageMap = [];
    /** @var string[] */
    protected $phrasesIndex = [];

    /**
     * XlsxWriter constructor.
     * @param TranslationManager $manager
     * @param string $pathToFile
     */
    public function __construct(TranslationManager $manager, string $pathToFile)
    {
        parent::__construct($manager);
        $this->pathToFile = $pathToFile;
        $this->languageMap = array_merge([static::CELL_SOURCE_NAME], array_keys($this->manager->getLangMap()));
        foreach ($this->languageMap as $idx => $langCode) {
            $this->languageMap[$langCode] = $idx + 1;
            unset($this->languageMap[$idx]);
        }
    }

    /**
     * @inheritDoc
     */
    public function write(DataRow $dataRow): bool
    {
        if (empty($this->sheet)) {
            $this->createWriter();
            $this->writeHeader();
        }
        $idx = $this->setSourceText($dataRow->sourceValue) + 2;

        $this->sheet->getActiveSheet()->setCellValueByColumnAndRow($this->languageMap[$dataRow->sourceLangCode], $idx, $dataRow->sourceValue);
        $this->sheet->getActiveSheet()->setCellValueByColumnAndRow($this->languageMap[$dataRow->destLangCode], $idx, $dataRow->destValue);
        return true;
    }

    /**
     * Add source text in list of phrases which will be translated
     * @param string $sourceText
     * @return int|null
     */
    public function setSourceText(string $sourceText): ?int
    {
        return $this->addPhrase($sourceText);
    }

    /**
     * @inheritDoc
     */
    public function finalize(): void
    {
        $this->writer->save($this->pathToFile);
    }

    /**
     * Initialize objects for writing files
     * @throws Exception
     */
    public function createWriter()
    {
        $this->sheet = new Spreadsheet();
        $this->writer = IOFactory::createWriter($this->sheet, 'Xlsx');
    }

    /**
     * Write file header
     */
    public function writeHeader(): void
    {
        foreach ($this->languageMap as $langCode => $cellId) {
            $langName = 'Исходный текст';
            if (key_exists($langCode, $this->manager->getLangMap())) {
                $langName = $this->manager->getLangMap()[$langCode];
            }
            $this->sheet->getActiveSheet()->setCellValueByColumnAndRow($cellId, 1, $langCode . ' - ' . $langName);
        }
    }

    /**
     * Add phrase to "index"
     * @param string $phrase
     * @return int|null
     */
    public function addPhrase(string $phrase): ?int
    {
        $phraseIdx = $this->getPhraseIndex($phrase);

        if ($phraseIdx === null) {
            $this->phrasesIndex[] = $phrase;
            return $this->getPhraseIndex($phrase);
        }
        return $phraseIdx;
    }

    /**
     * Returns phrase from "index"
     * @param string $phrase
     * @return int|null
     */
    public function getPhraseIndex(string $phrase): ?int
    {
        $idx = array_search($phrase, $this->phrasesIndex);
        if ($idx === false) {
            return null;
        }
        return $idx;
    }
}
