<?php

namespace FINDOLOGIC\Export\XML;

use FINDOLOGIC\Export\Exporter;

class XMLExporter extends Exporter
{
    /**
     * @inheritdoc
     */
    public function serializeItems($items, $start, $count, $total)
    {
        $page = new Page($start, $count, $total);
        $page->setAllItems($items);
        $xmlDocument = $page->getXml();

        return $xmlDocument->saveXML();
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile($targetDirectory, $items, $start, $count, $total)
    {
        $xmlString = $this->serializeItems($items, $start, $count, $total);
        $targetPath = sprintf('%s/findologic_%d_%d.xml', $targetDirectory, $start, $count);

        file_put_contents($targetPath, $xmlString);

        return $targetPath;
    }

    /**
     * @inheritdoc
     */
    public function createItem($id)
    {
        return new XMLItem($id);
    }
}
