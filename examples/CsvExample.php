<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BaseExample.php';
require_once __DIR__ . '/data/ExampleBaseItem.php';
require_once __DIR__ . '/data/ExampleProductItem.php';
require_once __DIR__ . '/data/ExampleContentItem.php';

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds a CSV export inspired by the FINDOLOGIC documentation, which can be found at
 * https://docs.findologic.com/doku.php?id=export_patterns:csv .
 */
class CsvExample extends BaseExample
{
    public function createExport(): string
    {
        $this->adaptExampleProducts();

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, [
            'sale',
            'novelty',
            'logo',
            'availability',
            'old_price',
            'Basic_rate_price',
            'variants',
            'file_type',
            'number_of_comments',
        ]);

        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }

    private function adaptExampleProducts(): void
    {
        foreach ($this->products as $product) {
            $product->images[''] = [current($product->images[''])];
        }
    }
}

$example = new CsvExample();

// Output the CSV content.
echo $example->createExport();
