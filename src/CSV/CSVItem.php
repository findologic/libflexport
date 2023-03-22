<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVItem extends Item
{
    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        throw new BadMethodCallException('CSVItem does not implement XML export.');
    }

    /**
     * @param int $imageCount
     * @inheritdoc
     */
    public function getCsvFragment(
        array $availableProperties = [],
        array $availableAttributes = [],
        int $imageCount = 1
    ): string {
        $id = $this->getId();
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

        $overriddenPrice = $this->getOverriddenPrice()->getCsvFragment();
        $groups = implode(',', array_map(function (Group $group): string {
            $groupName = $group->getCsvFragment();
            DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
            return self::sanitize($groupName);
        }, $this->groups));

        $ordernumbers = $this->buildOrdernumbers();
        $images = $this->buildImages($imageCount);
        $properties = $this->buildProperties($availableProperties);
        $attributes = $this->buildAttributes($availableAttributes);

        $data = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s%s%s\n",
            $id,
            '', // parentId
            $ordernumbers,
            $name,
            $summary,
            $description,
            $price,
            $overriddenPrice,
            $url,
            $keywords,
            $groups,
            $bonus,
            $salesFrequency,
            $dateAdded,
            $sort,
            $images,
            $properties,
            $attributes,
        );

        foreach ($this->variants as $variant) {
            $data .= $variant->getCsvFragment($availableProperties, $availableAttributes, $imageCount);
        }

        return $data;
    }

    private function buildOrdernumbers(): string
    {
        $orderNumbers = $this->ordernumbers->getCsvFragment();

        foreach ($this->variants as $variant) {
            $orderNumbers .= '|' . $variant->getOrdernumbers()->getCsvFragment();
        }

        return self::sanitize($orderNumbers);
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
        $imagesString = '';

        if (array_key_exists('', $this->images)) {
            $images = $this->images[''];

            for ($i = 0; $i < $imageCount; $i++) {
                $imageUrl = isset($images[$i]) ? $images[$i]->getCsvFragment() : '';
                $imagesString .= "\t" . $imageUrl;
            }
        }

        return $imagesString;
    }

    private static function sanitize($input, $stripTags = true): string
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        return preg_replace('/[\t\n\r]/', ' ', $input);
    }
}
