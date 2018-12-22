<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVExporter extends Exporter
{
    const HEADING = "id\tordernumber\tname\tsummary\tdescription\tprice\tinstead\tmaxprice\ttaxrate\turl\timage\t" .
        "attributes\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort";

    /**
     * @var array Names of properties; used for alignment of extra columns containing property values.
     */
    private $propertyKeys;

    public function __construct($itemsPerPage, $propertyKeys)
    {
        parent::__construct($itemsPerPage);

        $this->propertyKeys = $propertyKeys;
    }

    /**
     * @inheritdoc
     */
    public function serializeItems($items, $start = 0, $count = 0, $total = 0)
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

    /**
     * @inheritdoc
     */
    public function serializeItemsToFile($targetDirectory, $items, $start = 0, $count = 0, $total = 0)
    {
        $csvString = $this->serializeItems($items, $start, $count, $total);
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
