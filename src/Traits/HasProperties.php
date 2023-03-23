<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;

trait HasProperties
{
    /** @var array<string, string[]> */
    protected array $properties = [];

    /**
     * @return array<string, string[]>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addProperty(Property $property): void
    {
        if (count($property->getAllValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Property', $property->getKey());
        }

        foreach ($property->getAllValues() as $usergroup => $value) {
            $this->checkUsergroupString($usergroup);

            // No need to check if there are duplicate values for a single property and usergroup, because
            // Property::addValue() already takes care of that.

            $this->properties[$usergroup][$property->getKey()] = $value;
        }
    }
}
