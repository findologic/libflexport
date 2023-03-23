<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Image;
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
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $id = $this->getId();
        $name = self::sanitize($this->name->getCsvFragment($csvConfig));
        $summary = self::sanitize($this->summary->getCsvFragment($csvConfig));
        $description = self::sanitize($this->description->getCsvFragment($csvConfig));
        $price = $this->price->getCsvFragment($csvConfig);
        $url = self::sanitize($this->url->getCsvFragment($csvConfig));
        $keywords = self::sanitize($this->keywords->getCsvFragment($csvConfig));
        $bonus = self::sanitize($this->bonus->getCsvFragment($csvConfig));
        $salesFrequency = self::sanitize($this->salesFrequency->getCsvFragment($csvConfig));
        $dateAdded = self::sanitize($this->dateAdded->getCsvFragment($csvConfig));
        $sort = self::sanitize($this->sort->getCsvFragment($csvConfig));

        $overriddenPrice = $this->getOverriddenPrice()->getCsvFragment($csvConfig);
        $groups = implode(',', array_map(function (Group $group) use ($csvConfig): string {
                $groupName = $group->getCsvFragment($csvConfig);
                DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
                return self::sanitize($groupName);
        }, $this->groups));

        $ordernumbers = $this->buildOrdernumbers($csvConfig);
        $images = $this->buildImages($csvConfig, Image::TYPE_DEFAULT);
        $thumbnails = $this->buildImages($csvConfig, Image::TYPE_THUMBNAIL);
        $properties = $this->buildProperties($csvConfig);
        $attributes = $this->buildAttributes($csvConfig);

        $data = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%s\t%s%s%s%s%s\n",
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
            $thumbnails,
            $properties,
            $attributes,
        );

        foreach ($this->variants as $variant) {
            $data .= $variant->getCsvFragment($csvConfig);
        }

        return $data;
    }

    private function buildOrdernumbers(CSVConfig $csvConfig): string
    {
        $orderNumbers = $this->ordernumbers->getCsvFragment($csvConfig);

        foreach ($this->variants as $variant) {
            $orderNumbers .= '|' . $variant->getOrdernumbers()->getCsvFragment($csvConfig);
        }

        return self::sanitize($orderNumbers);
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

    private function buildImages(CSVConfig $csvConfig, string $type): string
    {
        $imagesString = '';

        if (array_key_exists('', $this->images)) {
            $imagesOfType = array_filter(
                $this->images[''],
                static fn(Image $image) => $image->getType() === $type
            );
            $images = array_values($imagesOfType);

            for ($i = 0; $i < $csvConfig->getImageCount(); $i++) {
                $imageUrl = isset($images[$i]) ? $images[$i]->getCsvFragment($csvConfig) : '';
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
