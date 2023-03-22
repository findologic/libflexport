<?php

namespace FINDOLOGIC\Export\XML;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Exceptions\BaseImageMissingException;
use FINDOLOGIC\Export\Exceptions\ImagesWithoutUsergroupMissingException;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class XMLItem extends Item
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

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
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

        $itemElem->appendChild($this->buildProperties($document));
        $itemElem->appendChild($this->buildAttributes($document));
        $itemElem->appendChild($this->buildImages($document));
        $itemElem->appendChild($this->buildGroups($document));
        $itemElem->appendChild($this->buildVariants($document));

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
    private function buildImages(DOMDocument $document): DOMElement
    {
        $allImagesElem = XMLHelper::createElement($document, 'allImages');

        if ($this->images) {
            if (array_key_exists('', $this->images)) {
                foreach ($this->images as $usergroup => $images) {
                    $usergroupImagesElem = XMLHelper::createElement($document, 'images');

                    if ($usergroup) {
                        $usergroupImagesElem->setAttribute('usergroup', $usergroup);
                    }

                    $allImagesElem->appendChild($usergroupImagesElem);

                    if (XMLItem::validateImages($images)) {
                        /** @var Image $image */
                        foreach ($images as $image) {
                            $usergroupImagesElem->appendChild($image->getDomSubtree($document));
                        }
                    }
                }
            } else {
                throw new ImagesWithoutUsergroupMissingException();
            }
        }

        return $allImagesElem;
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

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param DOMDocument $document
     * @return DOMElement
     */
    private function buildVariants(DOMDocument $document): DOMElement
    {
        $variants = XMLHelper::createElement($document, 'variants');

        foreach ($this->variants as $variant) {
            $variants->appendChild($variant->getDomSubtree($document));
        }

        return $variants;
    }

    /**
     * Checks if there is at least one image of type default
     *
     * @param Image[] $images The images to validate.
     * @return boolean Whether the images are valid or not.
     * @throws BaseImageMissingException
     */
    private static function validateImages(array $images): bool
    {
        foreach ($images as $image) {
            if ($image->getType() === Image::TYPE_DEFAULT) {
                return true;
            }
        }

        throw new BaseImageMissingException();
    }
}
