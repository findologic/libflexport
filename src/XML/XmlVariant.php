<?php

namespace FINDOLOGIC\Export\XML;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class XmlVariant extends Variant
{
    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        throw new BadMethodCallException('XmlVariant does not implement CSV export.');
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        $itemElem = XMLHelper::createElement($document, 'variant', ['id' => $this->id]);
        $document->appendChild($itemElem);

        $itemElem->appendChild($this->name->getDomSubtree($document));
        if (count($this->price->getValues())) {
            $itemElem->appendChild($this->price->getDomSubtree($document));
        }
        if (count($this->overriddenPrice->getValues())) {
            $itemElem->appendChild($this->overriddenPrice->getDomSubtree($document));
        }
        $itemElem->appendChild($this->ordernumbers->getDomSubtree($document));

        $itemElem->appendChild($this->buildXmlProperties($document));
        $itemElem->appendChild($this->buildXmlAttributes($document));
        $itemElem->appendChild($this->buildXmlGroups($document));

        return $itemElem;
    }
}
