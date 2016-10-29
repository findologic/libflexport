<?php

namespace FINDOLOGIC\Export\XML;


use FINDOLOGIC\Export\Exporter;

class XMLExporter extends Exporter
{
    /**
     * @inheritdoc
     */
    public function serializeItems($items, $start, $total)
    {
        $page = new Page($start, count($items), $total);
        $page->setAllItems($items);
        $xmlDocument = $page->getXml();

        return $xmlDocument->saveXML();
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile($targetDirectory, $items, $start, $total)
    {
        $xmlString = $this->serializeItems($items, $start, $total);
        $targetPath = sprintf('%s/findologic_%d_%d.xml', $targetDirectory, $start, count($items));

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