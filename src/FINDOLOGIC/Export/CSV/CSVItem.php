<?php

namespace FINDOLOGIC\Export\CSV;


use FINDOLOGIC\Export\Data\Item;

class CSVItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        throw new \BadMethodCallException('CSVItem does not implement XML export.');
    }
}