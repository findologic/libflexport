<?php

require_once __DIR__ . '/BaseExample.php';

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds a CSV export inspired by the FINDOLOGIC documentation, which can be found at
 * https://docs.findologic.com/doku.php?id=export_patterns:csv .
 */
class CsvExample extends BaseExample
{
    public function createExport(): string
    {
        $csvConfig = new CSVConfig(
            [
                'sale',
                'novelty',
                'logo',
                'availability',
                'old_price',
                'Basic_rate_price',
                'variants',
                'file_type',
                'number_of_comments',
                'badge',
            ],
            [
                'cat',
                'cat_url',
                'brand',
                'color',
                'type',
            ],
            3
        );

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, $csvConfig);

        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }
}

$example = new CsvExample();

// Output the CSV content.
echo $example->createExport();
