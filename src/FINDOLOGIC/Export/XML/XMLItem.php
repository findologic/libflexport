<?php

namespace FINDOLOGIC\Export\XML;


use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class XMLItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getCsvFragment()
    {
        throw new \BadMethodCallException('CSVItem does not implement XML export.');
    }

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
        $itemElem->appendChild($this->keywords->getDomSubtree($document));
        $itemElem->appendChild($this->ordernumbers->getDomSubtree($document));

        $itemElem->appendChild($this->buildProperties($document));
        $itemElem->appendChild($this->buildAttributes($document));
        $itemElem->appendChild($this->buildImages($document));
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

    private function buildImages(\DOMDocument $document)
    {
        $allImagesElem = XMLHelper::createElement($document, 'allImages');

        foreach ($this->images as $usergroup => $images) {
            $usergroupImagesElem = XMLHelper::createElement($document, 'images');
            if ($usergroup) {
                $usergroupImagesElem->setAttribute('usergroup', $usergroup);
            }
            $allImagesElem->appendChild($usergroupImagesElem);

            /** @var Image $image */
            foreach ($images as $image) {
                $usergroupImagesElem->appendChild($image->getDomSubtree($document));
            }
        }

        return $allImagesElem;
    }

    private function buildUsergroups(\DOMDocument $document)
    {
        $usergroups = XMLHelper::createElement($document, 'usergroups');

        /** @var Usergroup $usergroup */
        foreach ($this->usergroups as $usergroup) {
            $usergroups->appendChild($usergroup->getDomSubtree($document));
        }

        return $usergroups;
    }
}