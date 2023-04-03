<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\XMLHelper;

trait HasProperties
{
    /** @var array<string, mixed> */
    protected array $properties = [];

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addProperty(Property $property): void
    {
        if ($property->getAllValues() === []) {
            throw new EmptyElementsNotAllowedException('Property', $property->getKey());
        }

        foreach ($property->getAllValues() as $usergroup => $value) {
            $this->checkUsergroupString($usergroup);

            // No need to check if there are duplicate values for a single property and usergroup, because
            // Property::addValue() already takes care of that.

            $this->properties[$usergroup][$property->getKey()] = $value;
        }
    }

    protected function buildCsvProperties(CSVConfig $csvConfig): string
    {
        $propertiesString = '';

        foreach ($csvConfig->getAvailableProperties() as $availableProperty) {
            if (array_key_exists($availableProperty, $this->properties[''])) {
                $propertiesString .= "\t" . DataHelper::sanitize((string) $this->properties[''][$availableProperty]);
            } else {
                $propertiesString .= "\t";
            }
        }

        return $propertiesString;
    }

    protected function buildXmlProperties(DOMDocument $document): DOMElement
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

                $valueElem = XMLHelper::createElementWithText($document, 'value', (string) $value);
                $propertyElem->appendChild($valueElem);
            }
        }

        return $allProps;
    }
}
