<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVVariant extends Variant
{
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        throw new BadMethodCallException('CSVItem does not implement XML export.');
    }

    public function getCsvFragment(
        array $availableProperties = [],
        array $availableAttributes = [],
        int $imageCount = 1
    ): string {
        $id = $this->getId();
        $parentId = $this->getParentId();
        $ordernumbers = self::sanitize($this->ordernumbers->getCsvFragment());
        $name = self::sanitize($this->name->getCsvFragment());
        $price = $this->price->getCsvFragment();
        $overriddenPrice = $this->getOverriddenPrice()->getCsvFragment();

        $groups = implode(',', array_map(function (Group $group): string {
            $groupName = $group->getCsvFragment();
            DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
            return self::sanitize($groupName);
        }, $this->groups));

        $images = $this->buildImages($imageCount);
        $properties = $this->buildProperties($availableProperties);
        $attributes = $this->buildAttributes($availableAttributes);

        return sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s%s\t%s\t%s\t%s\t%s\t%s\t%s%s%s\n",
            $id,
            $parentId,
            $ordernumbers,
            $name,
            '',
            '',
            $price,
            $overriddenPrice,
            '',
            $images,
            '',
            $groups,
            '',
            '',
            '',
            '',
            $properties,
            $attributes,
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

    private function buildAttributes(array $availableAttributes): string
    {
        $attributesString = '';

        foreach ($availableAttributes as $availableAttribute) {
            if (array_key_exists($availableAttribute, $this->attributes)) {
                $sanitizedValues = array_map(
                    function (string $value) {
                        $sanitized = self::sanitize($value);
                        return str_replace(',', '\,', $sanitized);
                    },
                    $this->attributes[$availableAttribute]->getValues()
                );

                $attributesString .= "\t" . self::sanitize(implode(',', $sanitizedValues));
            } else {
                $attributesString .= "\t";
            }
        }

        return $attributesString;
    }

    private function buildImages(int $imageCount): string
    {
        return str_repeat("\t", $imageCount);
    }

    private static function sanitize($input, $stripTags = true): string
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        return preg_replace('/[\t\n\r]/', ' ', $input);
    }
}
