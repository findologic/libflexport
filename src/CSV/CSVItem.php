<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        throw new BadMethodCallException('CSVItem does not implement XML export.');
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $data = sprintf(
            CSVExporter::LINE_TEMPLATE,
            $this->getId(),
            '', // parentId
            $this->buildAllOrdernumbers($csvConfig),
            DataHelper::sanitize($this->name->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->summary->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->description->getCsvFragment($csvConfig)),
            $this->price->getCsvFragment($csvConfig),
            $this->getOverriddenPrice()->getCsvFragment($csvConfig),
            DataHelper::sanitize($this->url->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->keywords->getCsvFragment($csvConfig)),
            $this->buildCsvGroups($csvConfig),
            DataHelper::sanitize($this->bonus->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->salesFrequency->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->dateAdded->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->sort->getCsvFragment($csvConfig)),
            DataHelper::sanitize($this->visibility->getCsvFragment($csvConfig)),
            $this->buildCsvImages($csvConfig),
            $this->buildCsvProperties($csvConfig),
            $this->buildCsvAttributes($csvConfig),
        );

        foreach ($this->variants as $variant) {
            $data .= $variant->getCsvFragment($csvConfig);
        }

        return $data;
    }

    private function buildAllOrdernumbers(CSVConfig $csvConfig): string
    {
        $orderNumbers = $this->buildCsvOrdernumbers($csvConfig);

        foreach ($this->variants as $variant) {
            $variantOrdernumbers = DataHelper::sanitize($variant->getOrdernumbers()->getCsvFragment($csvConfig));
            if (strlen($variantOrdernumbers)) {
                $orderNumbers .= '|' . $variantOrdernumbers;
            }
        }

        return $orderNumbers;
    }
}
