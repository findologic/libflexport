<?php

namespace FINDOLOGIC\Export\CSV;


use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;

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
    public function getCsvFragment()
    {
        $ordernumbers = $this->ordernumbers->getCsvFragment();
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

        $instead = ''; // TODO
        $maxPrice = ''; // TODO
        $taxRate = ''; // TODO
        $groups = ''; // TODO


        $image = $this->buildImages();
        $attributes = $this->buildAttributes();
        $properties = $this->buildProperties();

        $line = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
            $this->id, $ordernumbers, $name, $summary, $description, $price, $instead, $maxPrice, $taxRate,
            $url, $image, $attributes, $keywords, $groups, $bonus, $salesFrequency, $dateAdded, $sort, $properties);

        return $line;
    }

    private function buildProperties()
    {
        // TODO

        return '';
    }

    private function buildAttributes()
    {
        $attributes = '';

        /** @var Attribute $attribute */
        foreach ($this->attributes as $attribute) {
            $attributes .= sprintf('%s&', $attribute->getCsvFragment());
        }

        $attributes = rtrim($attributes, '&');

        return $attributes;
    }

    private function buildImages()
    {
        // Use the first available image that is not restricted by usergroup.
        if (array_key_exists('', $this->images) && count($this->images['']) > 0) {
            $imageUrl = $this->images[''][0];
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

        $sanitized = preg_replace('/\t/', ' ', $input);

        return $sanitized;
    }
}