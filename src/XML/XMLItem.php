<?php

namespace FINDOLOGIC\Export\XML;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Helpers\XMLHelper;

final class XMLItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        throw new BadMethodCallException('XMLItem does not implement CSV export.');
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        $itemElem = XMLHelper::createElement($document, 'item', ['id' => $this->id]);
        $document->appendChild($itemElem);

        $itemElem->appendChild($this->name->getDomSubtree($document));
        $itemElem->appendChild($this->summary->getDomSubtree($document));
        $itemElem->appendChild($this->description->getDomSubtree($document));
        if (count($this->price->getValues())) {
            $itemElem->appendChild($this->price->getDomSubtree($document));
        }
        if (count($this->overriddenPrice->getValues())) {
            $itemElem->appendChild($this->overriddenPrice->getDomSubtree($document));
        }
        $itemElem->appendChild($this->url->getDomSubtree($document));
        $itemElem->appendChild($this->bonus->getDomSubtree($document));
        $itemElem->appendChild($this->salesFrequency->getDomSubtree($document));
        $itemElem->appendChild($this->dateAdded->getDomSubtree($document));
        $itemElem->appendChild($this->sort->getDomSubtree($document));
        $itemElem->appendChild($this->keywords->getDomSubtree($document));
        $itemElem->appendChild($this->ordernumbers->getDomSubtree($document));
        $itemElem->appendChild($this->visibility->getDomSubtree($document));

        $itemElem->appendChild($this->buildXmlProperties($document));
        $itemElem->appendChild($this->buildXmlAttributes($document));
        $itemElem->appendChild($this->buildXmlImages($document));
        $itemElem->appendChild($this->buildXmlGroups($document));
        $itemElem->appendChild($this->buildXmlVariants($document));

        return $itemElem;
    }
}
