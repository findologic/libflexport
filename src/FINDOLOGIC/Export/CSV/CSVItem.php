<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Helpers\DataHelper;
use InvalidArgumentException;

class CSVItem extends Item
{
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        throw new BadMethodCallException('CSVItem does not implement XML export.');
    }

    public function getCsvFragment(array $availableProperties = []): string
    {
        $id = $this->getId();
        $ordernumbers = self::sanitize($this->ordernumbers->getCsvFragment());
        $name = self::sanitize($this->name->getCsvFragment());
        $summary = self::sanitize($this->summary->getCsvFragment());
        $description = self::sanitize($this->description->getCsvFragment());
        $price = $this->price->getCsvFragment();
        $url = self::sanitize($this->url->getCsvFragment());
        $keywords = self::sanitize($this->keywords->getCsvFragment());
        $bonus = self::sanitize($this->bonus->getCsvFragment());
        $salesFrequency = self::sanitize($this->salesFrequency->getCsvFragment());
        $dateAdded = self::sanitize($this->dateAdded->getCsvFragment());
        $sort = self::sanitize($this->sort->getCsvFragment());

        $instead = $this->getInsteadPrice();
        $maxPrice = $this->getMaxPrice();
        $taxRate = $this->getTaxRate();
        $groups = implode(',', array_map(static function (Usergroup $group): string {
            /** @var Usergroup $group */
            $groupName = $group->getCsvFragment();
            DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
            return self::sanitize($groupName);
        }, $this->usergroups));

        $image = $this->buildImages();
        $attributes = $this->buildAttributes();
        $properties = $this->buildProperties($availableProperties);

        return sprintf(
            "%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s\n",
            $id,
            $ordernumbers,
            $name,
            $summary,
            $description,
            $price,
            $instead,
            $maxPrice,
            $taxRate,
            $url,
            $image,
            $attributes,
            $keywords,
            $groups,
            $bonus,
            $salesFrequency,
            $dateAdded,
            $sort,
            $properties
        );
    }

    private function buildProperties(array $availableProperties): string
    {
        $propertiesString = '';

        foreach ($availableProperties as $availableProperty) {
            if (array_key_exists($availableProperty, $this->properties[''])) {
                $propertiesString .= "\t" . self::sanitize($this->properties[''][$availableProperty]);
            } else {
                $propertiesString .= "\t";
            }
        }

        return $propertiesString;
    }

    private function buildAttributes(): string
    {
        $attributes = [];

        /** @var Attribute $attribute */
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->getCsvFragment();
        }

        $attributes = implode('&', $attributes);

        return $attributes;
    }

    private function buildImages(): string
    {
        // Use the first available image that is not restricted by usergroup. If more than one usergroup-less image
        // exists, cause an error because it's no longer certain which one is intended to be used.
        if (array_key_exists('', $this->images)) {
            if (count($this->images['']) === 1) {
                /** @var Image $image */
                $image = $this->images[''][0];
                $imageUrl = $image->getCsvFragment();
            } else {
                throw new InvalidArgumentException(
                    'Zero or multiple images without usergroup associated with item. ' .
                    'Cannot generate CSV if there is not one definitive image set.'
                );
            }
        } else {
            $imageUrl = '';
        }

        return $imageUrl;
    }

    private static function sanitize($input, $stripTags = true): string
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        return preg_replace('/[\t\n]/', ' ', $input);
    }
}
