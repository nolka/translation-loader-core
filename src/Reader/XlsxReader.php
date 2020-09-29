<?php

namespace TranslationLoader\Reader;

use Generator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use TranslationLoader\Data\DataRow;
use TranslationLoader\TranslationManager;
use TranslationLoader\Exception\FileNotFoundException;

/**
 * Used for reading translations from xlsx
 * @package TranslationLoader\Reader
 */
class XlsxReader extends BaseReader implements TranslationReaderInterface
{
    /** @var string */
    protected string $filePath;
    /** @var Spreadsheet */
    protected Spreadsheet $spreadsheet;
    /** @var array string[] */
    protected array $errors = [];
    /** @var array */
    protected array $languageMap = [];

    /**
     * XlsxReader constructor.
     * @param TranslationManager $manager
     * @param string $pathToFile
     * @throws FileNotFoundException
     */
    public function __construct(TranslationManager $manager, string $pathToFile)
    {
        $this->filePath = $pathToFile;
        if (!file_exists($this->filePath)) {
            throw new FileNotFoundException("File not found: {$this->filePath}!");
        }

        parent::__construct($manager);
    }

    /**
     * @inheritDoc
     */
    public function read(): Generator
    {
        if (empty($this->spreadsheet)) {
            $this->createReader();
        }
        foreach ($this->spreadsheet->getActiveSheet()->getRowIterator() as $rowId => $row) {
            if ($rowId == 1) {
                foreach ($row->getCellIterator() as $cellId => $cell) {
                    $langCode = $this->getLangCode($cell->getValue());
                    if (!array_key_exists($langCode, $this->getAllowedLangs())) {
                        $this->addError("Unknown language skipped: {$langCode}");
                        continue;
                    }
                    $this->languageMap[$cellId] = $this->getLangCode($cell->getValue());
                }
                continue;
            }
            $sourceTranslationIdx = $this->getSourceTranslationId();
            $sourceValue = $sourceLang = '';

            foreach ($row->getCellIterator() as $cellId => $cell) {

                if ($cellId == $sourceTranslationIdx) {
                    $sourceValue = trim($cell->getValue());
                    $sourceLang = $this->languageMap[$cellId];
                    continue;
                }
                if (!array_key_exists($cellId, $this->languageMap)) {
                    continue;
                }
                if ($this->languageMap[$cellId] == static::CELL_SOURCE_NAME) {
                    continue;
                }

                $dataRow = new DataRow();
                $dataRow->sourceValue = $sourceValue;
                $dataRow->sourceLangCode = $sourceLang;
                $dataRow->destValue = trim($cell->getValue());
                $dataRow->destLangCode = $this->languageMap[$cellId];

                yield $dataRow;
            }
        }
    }

    /**
     * Массив доступных языков для перевода
     * @return array
     */
    protected function getAllowedLangs(): array
    {
        return array_merge([static::CELL_SOURCE_NAME => static::CELL_SOURCE_NAME], $this->manager->getLangMap());
    }

    /**
     * Возвращает индекс столбца с исходными текстами в файле с переводами
     * @return int
     */
    protected function getSourceTranslationId(): int
    {
        return array_search(static::CELL_SOURCE_NAME, $this->languageMap);
    }

    /**
     * Парсит код языка из названия заголовка
     * @param string $value
     * @return string
     */
    protected function getLangCode(string $value): string
    {
        if (static::CELL_SOURCE_NAME === substr($value, 0, mb_strlen(static::CELL_SOURCE_NAME))) {
            return substr($value, 0, mb_strlen(static::CELL_SOURCE_NAME));
        }
        return substr($value, 0, 2);
    }

    /**
     * Создает экземпляр читателя excel файла
     * @throws Exception
     */
    protected function createReader(): void
    {
        $reader = IOFactory::createReaderForFile($this->filePath);
        $reader->setReadDataOnly(true);
        $this->spreadsheet = $reader->load($this->filePath);
    }

    /**
     * Добавить ошибку
     * @param string $message
     */
    protected function addError(string $message): void
    {
        $this->errors[] = $message;
    }

}
