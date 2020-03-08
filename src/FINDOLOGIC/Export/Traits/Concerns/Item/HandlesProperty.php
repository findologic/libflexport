<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;

trait HandlesProperty
{
    protected $properties = [];

    /**
     * @param Property $property The property element to add to the item.
     */
    public function addProperty(Property $property): void
    {
        if (count($property->getAllValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Property', $property->getKey());
        }

        foreach ($property->getAllValues() as $usergroup => $value) {
            // No need to check if there are duplicate values for a single property and usergroup, because
            // Property::addValue() already takes care of that.

            $this->properties[$usergroup][$property->getKey()] = $value;
        }
    }
}
