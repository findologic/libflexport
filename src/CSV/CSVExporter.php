<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVExporter extends Exporter
{
    private const HEADING = "id\tparent_id\tordernumber\tname\tsummary\tdescription\tprice\toverriddenPrice\turl\t" .
        "keywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort";

    /**
     * @var string[] Names of properties; used for alignment of extra columns containing property values.
     */
    private array $propertyKeys;

    /**
     * @var string[] Names of attributes; used for alignment of extra columns containing attribute values.
     */
    private array $attributeKeys;

    private int $imageCount;

    public function __construct(int $itemsPerPage, array $propertyKeys, array $attributeKeys, int $imageCount)
    {
        parent::__construct($itemsPerPage);

        $this->propertyKeys = DataHelper::checkForInvalidCsvColumnKeys($propertyKeys);
        $this->attributeKeys = DataHelper::checkForInvalidCsvColumnKeys($attributeKeys);
        $this->imageCount = $imageCount;
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
            $export .= $item->getCsvFragment($this->propertyKeys, $this->attributeKeys, $this->imageCount);
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
     * @param string $parentId
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
            $this->getImageHeadingPart() .
            $this->getPropertyHeadingPart() .
            $this->getAttributeHeadingPart() .
            "\n";
    }

    /**
     * Returns the image part of the heading line.
     */
    protected function getImageHeadingPart(): string
    {
        $imageHeading = '';

        for ($i = 0; $i < $this->imageCount; $i++) {
            $imageHeading .= "\t" . 'image' . $i;
        }

        return $imageHeading;
    }

    /**
     * Returns the property part of the heading line.
     */
    protected function getPropertyHeadingPart(): string
    {
        $propertyHeading = '';

        foreach ($this->propertyKeys as $propertyKey) {
            $propertyHeading .= "\t" . 'prop_' . $propertyKey;
        }

        return $propertyHeading;
    }

    /**
     * Returns the attribute part of the heading line.
     *
     * @return string
     */
    protected function getAttributeHeadingPart(): string
    {
        $attributeHeading = '';

        foreach ($this->attributeKeys as $attributeKey) {
            $attributeHeading .= "\t" . 'attrib_' . $attributeKey;
        }

        return $attributeHeading;
    }
}
