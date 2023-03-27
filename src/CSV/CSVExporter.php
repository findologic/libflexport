<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Exporter;

class CSVExporter extends Exporter
{
    private const HEADING = "id\tparent_id\tordernumber\tname\tsummary\tdescription\tprice\toverriddenPrice\turl\t" .
        "keywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort\tvisibility";

    public const LINE_TEMPLATE = "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s%s%s\n";

    private CSVConfig $csvConfig;

    public function __construct(int $itemsPerPage, CSVConfig $csvConfig)
    {
        parent::__construct($itemsPerPage);

        $this->csvConfig = $csvConfig;
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
            $export .= $item->getCsvFragment($this->csvConfig);
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

        $targetPath = sprintf('%s/%s.csv', $targetDirectory, $this->fileNamePrefix);

        // Clear CSV contents if a new export starts. Don't do this for further pagination steps, to prevent
        // overriding the file itself, causing it to clear all contents except the new items.
        file_put_contents($targetPath, $csvString, $start > 0 ? FILE_APPEND : 0);

        return $targetPath;
    }

    /**
     * @inheritdoc
     */
    public function createItem(string $id): Item
    {
        return new CSVItem($id);
    }

    /**
     * @inheritdoc
     */
    public function createVariant(string $id, string $parentId): Variant
    {
        return new CSVVariant($id, $parentId);
    }

    /**
     * Returns the heading line of a CSV document
     */
    protected function getHeadingLine(): string
    {
        return self::HEADING .
            $this->getNumberedHeadingPart($this->csvConfig->getImageCount(), 'image') .
            $this->getPrefixedHeadingPart($this->csvConfig->getAvailableProperties(), 'prop_') .
            $this->getPrefixedHeadingPart($this->csvConfig->getAvailableAttributes(), 'attrib_') .
            "\n";
    }

    /**
     * Returns the header part for the given numbered columns.
     */
    protected function getNumberedHeadingPart(int $count, string $columnName): string
    {
        $heading = '';

        for ($i = 0; $i < $count; $i++) {
            $heading .= "\t" . $columnName . $i;
        }

        return $heading;
    }

    /**
     * Returns the header part for the given prefixed column keys.
     */
    protected function getPrefixedHeadingPart(array $keys, string $prefix): string
    {
        $heading = '';

        foreach ($keys as $key) {
            $heading .= "\t" . $prefix . $key;
        }

        return $heading;
    }
}
