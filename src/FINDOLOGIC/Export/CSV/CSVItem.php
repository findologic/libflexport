<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Usergroup;

class CSVItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        throw new \BadMethodCallException('CSVItem does not implement XML export.');
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(array $availableProperties = [])
    {
        $that = $this; // Used in closure.

        $ordernumbers = $this->sanitize($this->ordernumbers->getCsvFragment());
        $name = $this->sanitize($this->name->getCsvFragment());
        $summary = $this->sanitize($this->summary->getCsvFragment());
        $description = $this->sanitize($this->description->getCsvFragment());
        $price = $this->price->getCsvFragment();
        $url = $this->sanitize($this->url->getCsvFragment());
        $keywords = $this->sanitize($this->keywords->getCsvFragment());
        $bonus = $this->sanitize($this->bonus->getCsvFragment());
        $salesFrequency = $this->sanitize($this->salesFrequency->getCsvFragment());
        $dateAdded = $this->sanitize($this->dateAdded->getCsvFragment());
        $sort = $this->sanitize($this->sort->getCsvFragment());

        $instead = $this->getInsteadPrice();
        $maxPrice = $this->getMaxPrice();
        $taxRate = $this->getTaxRate();
        $groups = implode(',', array_map(function ($group) use ($that) {
            /** @var $group Usergroup */
            return $that->sanitize($group->getCsvFragment());
        }, $this->usergroups));


        $image = $this->buildImages();
        $attributes = $this->buildAttributes();
        $properties = $this->buildProperties($availableProperties);

        $line = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s\n",
            $this->id,
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

        return $line;
    }

    private function buildProperties($availableProperties)
    {
        $propertiesString = '';

        foreach ($availableProperties as $availableProperty) {
            if (array_key_exists($availableProperty, $this->properties[''])) {
                $propertiesString .= "\t" . $this->sanitize($this->properties[''][$availableProperty]);
            } else {
                $propertiesString .= "\t";
            }
        }

        return $propertiesString;
    }

    private function buildAttributes()
    {
        $attributes = [];

        /** @var Attribute $attribute */
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->getCsvFragment();
        }

        $attributes = implode('&', $attributes);

        return $attributes;
    }

    private function buildImages()
    {
        // Use the first available image that is not restricted by usergroup. If more than one usergroup-less image
        // exists, cause an error because it's no longer certain which one is intended to be used.
        if (array_key_exists('', $this->images)) {
            if (count($this->images['']) === 1) {
                $imageUrl = $this->images[''][0]->getCsvFragment();
            } else {
                throw new \InvalidArgumentException(
                    'Zero or multiple images without usergroup associated with item. ' .
                    'Cannot generate CSV if there is not one definitive image set.'
                );
            }
        } else {
            $imageUrl = '';
        }

        return $imageUrl;
    }

    private function sanitize($input, $stripTags = true)
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        $sanitized = preg_replace('/[\t\n]/', ' ', $input);

        return $sanitized;
    }
}
