<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVExporter extends Exporter
{
    private const HEADING = "id\tordernumber\tname\tsummary\tdescription\tprice\tinstead\tmaxprice\ttaxrate\turl\t" .
        "image\tattributes\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort";

    /**
     * @var array Names of properties; used for alignment of extra columns containing property values.
     */
    private $propertyKeys;

    public function __construct($itemsPerPage, $propertyKeys)
    {
        parent::__construct($itemsPerPage);

        $this->propertyKeys = $propertyKeys;
    }

    public function serializeItems(array $items, int $start = 0, int $count = 0, int $total = 0): string
    {
        $export = '';
        // To enable pagination, don't write the heading if it's anything but the first page.
        if ($start === 0) {
            $export = self::HEADING;

            foreach ($this->propertyKeys as $propertyKey) {
                DataHelper::checkForIllegalCsvPropertyKeys($propertyKey);

                $export .= "\t" . $propertyKey;
            }
            $export .= "\n";
        }

        /** @var CSVItem $item */
        foreach ($items as $item) {
            $export .= $item->getCsvFragment($this->propertyKeys);
        }

        return $export;
    }

    public function serializeItemsToFile(
        string $targetDirectory,
        array $items,
        int $start = 0,
        int $count = 0,
        int $total = 0
    ): string {
        $csvString = $this->serializeItems($items, $start, $count, $total);
        $targetPath = sprintf('%s/findologic.csv', $targetDirectory);

        file_put_contents($targetPath, $csvString);

        return $targetPath;
    }

    public function createItem($id): Item
    {
        return new CSVItem($id);
    }
}
