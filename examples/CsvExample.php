<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds a CSV export inspired by the FINDOLOGIC documentation, which can be found at
 * https://docs.findologic.com/doku.php?id=export_patterns:csv .
 */
final class CsvExample extends BaseExample
{
    public function createExport(): string
    {
        $csvConfig = new CSVConfig(
            availableProperties: [
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
            availableAttributes: [
                'cat',
                'cat_url',
                'brand',
                'color',
                'ratingAvg',
                'ratingCount',
                'free_shipping',
                'type',
            ],
            imageCount: 3,
        );

        $exporter = Exporter::create(ExporterType::CSV, 20, $csvConfig);

        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }
}

$example = new CsvExample();

// Output the CSV content.
echo $example->createExport();
