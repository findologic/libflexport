<?php

namespace FINDOLOGIC\Export\XML;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class XmlVariant extends Variant
{
    /**
     * @param int $imageCount
     * @inheritdoc
     */
    public function getCsvFragment(
        array $availableProperties = [],
        array $availableAttributes = [],
        int $imageCount = 1
    ): string {
        throw new BadMethodCallException('XMLItem does not implement CSV export.');
    }

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

        $itemElem->appendChild($this->buildProperties($document));
        $itemElem->appendChild($this->buildAttributes($document));
        $itemElem->appendChild($this->buildGroups($document));

        return $itemElem;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param DOMDocument $document
     * @return DOMElement
     */
    private function buildProperties(DOMDocument $document): DOMElement
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

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param DOMDocument $document
     * @return DOMElement
     */
    private function buildAttributes(DOMDocument $document): DOMElement
    {
        $allAttributes = XMLHelper::createElement($document, 'allAttributes');

        $attributes = XMLHelper::createElement($document, 'attributes');
        $allAttributes->appendChild($attributes);

        /**
         * @var string $key
         * @var Attribute $attribute
         */
        foreach ($this->attributes as $attribute) {
            $attributes->appendChild($attribute->getDomSubtree($document));
        }

        return $allAttributes;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param DOMDocument $document
     * @return DOMElement
     */
    private function buildGroups(DOMDocument $document): DOMElement
    {
        $groups = XMLHelper::createElement($document, 'groups');

        /** @var Group $groups */
        foreach ($this->groups as $group) {
            $groups->appendChild($group->getDomSubtree($document));
        }

        return $groups;
    }
}
