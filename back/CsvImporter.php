<?php

declare(strict_types = 1);

namespace MyClass;

use Webmozart\Assert\Assert;

final class CsvImporter
{
    private int $counter = 0;
    private array $header = [];
    private array $valuesToCast = [];
    private array $data = [];

    public function __construct(
        private readonly int $longestRow = 10000,
        private readonly string $baseCastValue = 'string',
        private readonly int $firstRowIndex = 1,
        private readonly int $secondRowIndex = 2,
        private readonly string $separator = ',',
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array<mixed>
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    public function import(
        string $path,
        bool $hasHeader,
        bool $castValues,
        ?int $limit = null,
        bool $asObject = false
    ): void {
        $handle = $this->getHandle($path);

        while (($row = $this->getCsvContent($handle)) !== []) {
            if ($this->isDone($limit, $hasHeader)) {
                return;
            }

            $this->incrementCounter();

            if ($this->canSetHeader($hasHeader)) {
                $this->setHeader($row);
                continue;
            }

            $this->handleCastValues($hasHeader, $castValues, $row);

            $this->addRowToData($row, $asObject);
        }

        fclose($handle);
    }

    private function canSetHeader(bool $header): bool
    {
        return $header === true
            && $this->header === []
            && $this->counter === $this->firstRowIndex;
    }

    private function setHeader(array $row): void
    {
        array_map(function ($value): void {
            $this->header[] = mb_strtolower(str_replace(' ', '_', trim($value)));
        }, $row);
    }

    private function hasCastValues(bool $header, bool $cast): bool
    {
        return ($header === true && $this->counter === $this->secondRowIndex)
            || ($cast === true && $this->counter === $this->firstRowIndex);
    }

    private function setCastValues(array $row): void
    {
        array_map(function ($value): void {
            $value = strtolower(trim($value));

            if ($value === '') {
                $value = $this->baseCastValue;
            }

            $this->valuesToCast[] = $value;
        }, $row);
    }

    private function addRowToData(array $row, bool $asObject = false): void
    {
        $rowData = [];

        foreach ($row as $key => $value) {
            if (isset($this->header[$key], $this->valuesToCast[$key])) {
                $rowData[$this->header[$key]] = Converter::castTo($value, $this->valuesToCast[$key]);
                continue;
            }

            $rowData[$key] = $this->dealWithNull($value);
        }

        if ($asObject) {
            $this->data[] = (object)$rowData;
            return;
        }

        $this->data[] = $rowData;
    }

    private function dealWithNull(mixed $value): mixed
    {
        if (trim($value) === '') {
            return null;
        }

        return $value;
    }

    private function isDone(?int $limit, bool $hasHeader): bool
    {
        return ($limit !== null && ($this->counter === $limit && !$hasHeader))
            || ($limit !== null && $this->counter === ($limit + 1) && $hasHeader);
    }

    /**
     * @return resource
     */
    private function getHandle(string $path)
    {
        $handle = fopen($path, 'rb');
        Assert::notFalse($handle, 'file ' . $path . ' not found');
        return $handle;
    }

    /**
     * @param resource $handle
     * @return array<mixed>
     */
    private function getCsvContent($handle): array
    {
        $content = fgetcsv($handle, $this->longestRow, $this->separator);

        if ($content === false) {
            return [];
        }

        return $content;
    }

    /**
     * @param array $row
     */
    private function handleCastValues(bool $hasHeader, bool $castValues, array $row): void
    {
        if (!$this->hasCastValues($hasHeader, $castValues)) {
            return;
        }

        $this->setCastValues($row);
    }

    private function incrementCounter(): void
    {
        ++$this->counter;
    }
}