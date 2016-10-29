<?php

namespace FINDOLOGIC\Export\CSV;


use FINDOLOGIC\Export\Exporter;

class CSVExporter extends Exporter
{

    /**
     * @inheritdoc
     */
    public function serializeItems($items, $start, $total)
    {
        // TODO: Implement serializeItems() method.
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile($targetDirectory, $items, $start, $total)
    {
        // TODO: Implement serializeItemsToFile() method.
    }

    /**
     * @inheritdoc
     */
    public function createItem($id)
    {
        return new CSVItem($id);
    }
}