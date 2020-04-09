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

        $this->propertyKeys = DataHelper::checkForInvalidCsvPropertyKeys($propertyKeys);
    }

    /**
     * @inheritdoc
     */
    public function serializeItems(array $items, int $start = 0, int $count = 0, int $total = 0): string
    {
        $export = '';

        // To enable pagination, don't write the heading if it's anything but the first page.
        if ($start === 0) {
            $export = $this->getHeadingLine();
        }

        /** @var CSVItem $item */
        foreach ($items as $item) {
            $export .= $item->getCsvFragment($this->propertyKeys);
        }

        return $export;
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile(
        string $targetDirectory,
        array $items,
        int $start = 0,
        int $count = 0,
        int $total = 0
    ): string {
        $csvString = $this->serializeItems($items, $start, $count, $total);
        $targetPath = sprintf('%s/findologic.csv', $targetDirectory);

        // Clear CSV contents if a new export starts with start 0. Otherwise append the contents.
        file_put_contents($targetPath, $csvString, $start > 0 ? FILE_APPEND : 0);

        return $targetPath;
    }

    /**
     * @inheritdoc
     */
    public function createItem($id): Item
    {
        return new CSVItem($id);
    }

    /**
     * Returns the heading line of a CSV document
     *
     * @return string
     */
    protected function getHeadingLine(): string
    {
        return self::HEADING . $this->getPropertyHeadingPart() . "\n";
    }

    /**
     * Returns the property part of the heading line.
     *
     * @return string
     */
    protected function getPropertyHeadingPart(): string
    {
        $propertyHeading = '';

        foreach ($this->propertyKeys as $propertyKey) {
            $propertyHeading .= "\t" . $propertyKey;
        }

        return $propertyHeading;
    }
}
