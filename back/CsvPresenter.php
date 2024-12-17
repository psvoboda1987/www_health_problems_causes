<?php

declare(strict_types = 1);

namespace App\csv;

use MyClass\CsvImporter;

final class CsvPresenter
{
    public const PREFIX = 'viz ';

    public function __construct(
        private readonly CsvImporter $csvImporter,
    )
    {
    }

    private function getDataCsv(): array
    {
        $csvImporter = $this->getCsv();
        $data = $csvImporter->getData();
        $keys = array_flip(array_column($data, 'problém'));

        foreach ($data as $rowKey => $row) {
            foreach ($row as $value) {
                $data[$rowKey]['link'] = '';

                if (!str_starts_with($value, self::PREFIX)) {
                    continue;
                }

                $key = str_replace(self::PREFIX, '', $value);

                if (!array_key_exists($key, $keys)) {
                    continue;
                }

                $data[$rowKey]['link'] = $key;
            }
        }

        return $data;
    }

    private function getCsv(): CsvImporter
    {
        $this->csvImporter->import(__DIR__ . '/data.csv', true, false);
        return $this->csvImporter;
    }

    public static function getColor($iterator): string
    {
        return match (true) {
            $iterator % 3 === 0 => 'w3-text-green',
            $iterator % 2 === 0 => 'w3-text-blue',
            default => 'w3-text-orange',
        };
    }
}
