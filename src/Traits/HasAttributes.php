<?php

namespace FINDOLOGIC\Export\Traits;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Helpers\XMLHelper;

trait HasAttributes
{
    /** @var Attribute[] */
    protected array $attributes = [];

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Adds an attribute.
     *
     * E.g.
     * ```
     * $attr1 = Attribute('color', ['orange', 'yellow']);
     * $attr2 = Attribute('color', ['pink', 'orange']);
     *
     * $item->addAttribute($attr1);
     * $item->addAttribute($attr2);
     * // $item attributes will be: ['pink', 'orange']
     * ```
     *
     * @see addMergedAttribute if you want to merge values with the same key.
     */
    public function addAttribute(Attribute $attribute): void
    {
        if (count($attribute->getValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Attribute', $attribute->getKey());
        }

        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * Adds an attribute by merging attribute values with the same key.
     *
     * E.g.
     * ```
     * $attr1 = Attribute('color', ['orange', 'yellow']);
     * $attr2 = Attribute('color', ['pink', 'orange']);
     *
     * $item->addAttribute($attr1);
     * $item->addAttribute($attr2);
     * // $item attributes will be: ['orange', 'yellow', 'pink']
     * ```
     *
     * @see addAttribute if you don't want to merge values with the same key.
     */
    public function addMergedAttribute(Attribute $attribute): void
    {
        if (count($attribute->getValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Attribute', $attribute->getKey());
        }

        if (array_key_exists($attribute->getKey(), $this->attributes)) {
            $attribute = new Attribute(
                $attribute->getKey(),
                array_unique(array_merge($this->attributes[$attribute->getKey()]->getValues(), $attribute->getValues()))
            );
        }

        $this->attributes[$attribute->getKey()] = $attribute;
    }

    protected function buildCsvAttributes(CSVConfig $csvConfig): string
    {
        $attributesString = '';

        foreach ($csvConfig->getAvailableAttributes() as $availableAttribute) {
            if (array_key_exists($availableAttribute, $this->attributes)) {
                $attributesString .= "\t" . $this->attributes[$availableAttribute]->getCsvFragment($csvConfig);
            } else {
                $attributesString .= "\t";
            }
        }

        return $attributesString;
    }

    protected function buildXmlAttributes(DOMDocument $document): DOMElement
    {
        $allAttributes = XMLHelper::createElement($document, 'allAttributes');
        $attributes = XMLHelper::createElement($document, 'attributes');
        $allAttributes->appendChild($attributes);

        foreach ($this->attributes as $attribute) {
            $attributes->appendChild($attribute->getDomSubtree($document));
        }

        return $allAttributes;
    }
}
