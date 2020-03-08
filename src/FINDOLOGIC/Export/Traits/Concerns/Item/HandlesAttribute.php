<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;

trait HandlesAttribute
{
    protected $attributes = [];

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
     * @param Attribute $attribute The attribute element to add to the item.
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
}
