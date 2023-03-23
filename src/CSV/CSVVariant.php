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

    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $id = $this->getId();
        $parentId = $this->getParentId();
        $ordernumbers = self::sanitize($this->ordernumbers->getCsvFragment($csvConfig));
        $name = self::sanitize($this->name->getCsvFragment($csvConfig));
        $price = $this->price->getCsvFragment($csvConfig);
        $overriddenPrice = $this->getOverriddenPrice()->getCsvFragment($csvConfig);

        $groups = implode(',', array_map(function (Group $group) use ($csvConfig): string {
            $groupName = $group->getCsvFragment($csvConfig);
            DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
            return self::sanitize($groupName);
        }, $this->groups));

        $images = $this->buildImages($csvConfig->getImageCount());
        $thumbnails = $this->buildImages($csvConfig->getThumbnailCount());
        $properties = $this->buildProperties($csvConfig);
        $attributes = $this->buildAttributes($csvConfig);

        return sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s%s%s%s\n",
            $id,
            $parentId,
            $ordernumbers,
            $name,
            '',
            '',
            $price,
            $overriddenPrice,
            '',
            '',
            $groups,
            '',
            '',
            '',
            '',
            $images,
            $thumbnails,
            $properties,
            $attributes,
        );
    }

    private function buildProperties(CSVConfig $csvConfig): string
    {
        $propertiesString = '';

        foreach ($csvConfig->getAvailableProperties() as $availableProperty) {
            if (array_key_exists($availableProperty, $this->properties[''])) {
                $propertiesString .= "\t" . self::sanitize($this->properties[''][$availableProperty]);
            } else {
                $propertiesString .= "\t";
            }
        }

        return $propertiesString;
    }

    private function buildAttributes(CSVConfig $csvConfig): string
    {
        $attributesString = '';

        foreach ($csvConfig->getAvailableAttributes() as $availableAttribute) {
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

    private function buildImages(int $count): string
    {
        return str_repeat("\t", $count);
    }

    private static function sanitize(string $input, bool $stripTags = true): string
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        return preg_replace('/[\t\n\r]/', ' ', $input);
    }
}
