<?php

namespace FINDOLOGIC\Export\CSV;


use FINDOLOGIC\Export\Exporter;

class CSVExporter extends Exporter
{
    const HEADING = "id\tordernumber\tname\tsummary\tdescription\tprice\tinstead\tmaxprice\ttaxrate\turl\timage\t" .
        "attributes\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort\n";

    /**
     * @inheritdoc
     */
    public function serializeItems($items, $start, $total)
    {
        $export = self::HEADING;

        /** @var CSVItem $item */
        foreach ($items as $item) {
            $export .= $item->getCsvFragment();
        }

        return $export;
    }

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile($targetDirectory, $items, $start, $total)
    {
        $csvString = $this->serializeItems($items, $start, $total);
        $targetPath = sprintf('%s/findologic.csv', $targetDirectory);

        file_put_contents($targetPath, $csvString);

        return $targetPath;
    }

    /**
     * @inheritdoc
     */
    public function createItem($id)
    {
        return new CSVItem($id);
    }
}