<?php

namespace FINDOLOGIC\Export\XML;


use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class XMLItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $itemElem = XMLHelper::createElement($document, 'item', array('id' => $this->id));
        $document->appendChild($itemElem);

        $itemElem->appendChild($this->name->getDomSubtree($document));
        $itemElem->appendChild($this->summary->getDomSubtree($document));
        $itemElem->appendChild($this->description->getDomSubtree($document));
        $itemElem->appendChild($this->price->getDomSubtree($document));
        $itemElem->appendChild($this->url->getDomSubtree($document));
        $itemElem->appendChild($this->bonus->getDomSubtree($document));
        $itemElem->appendChild($this->salesFrequency->getDomSubtree($document));
        $itemElem->appendChild($this->dateAdded->getDomSubtree($document));
        $itemElem->appendChild($this->sort->getDomSubtree($document));

        // TODO: lots of stuff

        $itemElem->appendChild($this->buildProperties($document));
        $itemElem->appendChild($this->buildAttributes($document));
        $itemElem->appendChild($this->buildOrdernumbers($document));
        $itemElem->appendChild($this->buildImages($document));
        $itemElem->appendChild($this->buildKeywords($document));
        $itemElem->appendChild($this->buildUsergroups($document));

        return $itemElem;
    }

    private function buildProperties(\DOMDocument $document)
    {
        $allProps = XMLHelper::createElement($document, 'allProperties');

        foreach ($this->properties as $usergroup => $usergroupSpecificProperties) {
            $usergroupPropsElem = XMLHelper::createElement($document, 'properties');
            if ($usergroup) {
                $usergroupPropsElem->setAttribute('usergroup', $usergroup);
            }
            $allProps->appendChild($usergroupPropsElem);

            foreach ($usergroupSpecificProperties as $key => $value) {
                $propertyElem = XMLHelper::createElement($document, 'property');
                $usergroupPropsElem->appendChild($propertyElem);

                $keyElem = XMLHelper::createElementWithText($document, 'key', $key);
                $propertyElem->appendChild($keyElem);

                $valueElem = XMLHelper::createElementWithText($document, 'value', $value);
                $propertyElem->appendChild($valueElem);
            }
        }

        return $allProps;
    }

    private function buildAttributes(\DOMDocument $document)
    {
        $allAttributes = XMLHelper::createElement($document, 'allAttributes');

        $attributes = XMLHelper::createElement($document, 'attributes');
        $allAttributes->appendChild($attributes);

        /**
         * @var string $key
         * @var Attribute $attribute
         */
        foreach ($this->attributes as $key => $attribute) {
            $attributes->appendChild($attribute->getDomSubtree($document));
        }

        return $allAttributes;
    }

    private function buildOrdernumbers(\DOMDocument $document)
    {
        $allOrdernumbers = XMLHelper::createElement($document, 'allOrdernumbers');

        // TODO

        return $allOrdernumbers;
    }

    private function buildImages(\DOMDocument $document)
    {
        $allImages = XMLHelper::createElement($document, 'allImages');

        // TODO

        return $allImages;
    }

    private function buildKeywords(\DOMDocument $document)
    {
        $allKeywords = XMLHelper::createElement($document, 'allKeywords');

        // TODO

        return $allKeywords;
    }

    private function buildUsergroups(\DOMDocument $document)
    {
        $usergroups = XMLHelper::createElement($document, 'usergroups');

        // TODO

        return $usergroups;
    }
}