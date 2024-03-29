<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\XML;

use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Exporter;

final class XMLExporter extends Exporter
{
    /**
     * @inheritdoc
     */
    public function serializeItems(array $items, int $start, int $count, int $total): string
    {
        $page = new Page($start, $count, $total);
        $page->setAllItems($items);
        $xmlDocument = $page->getXml();

        return $xmlDocument->saveXML();
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile(
        string $targetDirectory,
        array $items,
        int $start,
        int $count,
        int $total
    ): string {
        $xmlString = $this->serializeItems($items, $start, $count, $total);
        $targetPath = sprintf('%s/%s_%d_%d.xml', $targetDirectory, $this->fileNamePrefix, $start, $count);

        file_put_contents($targetPath, $xmlString);

        return $targetPath;
    }

    /**
     * @inheritdoc
     */
    public function createItem(string $id): Item
    {
        return new XMLItem($id);
    }

    /**
     * @inheritdoc
     */
    public function createVariant(string $id, string $parentId): Variant
    {
        return new XmlVariant($id, $parentId);
    }
}
