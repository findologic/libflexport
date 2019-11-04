<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ItemProduct.php';
require_once __DIR__ . '/ItemContent.php';

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds a xml export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link https://docs.findologic.com/doku.php?id=export_patterns:xml#example_xml
 */
class XmlExample
{
    /**
     * @var Item
     */
    private $item;

    public function createExport(): string
    {
        $items = [];
        $exporter = Exporter::create(Exporter::TYPE_XML);

        $products[] = new ItemProduct();
        $products[] = new ItemContent();

        foreach ($products as $product) {
            $this->item = $exporter->createItem($product->id);

            $this->addOrderNumbers($product);
            $this->addNames($product);
            $this->addSummaries($product);
            $this->addDescriptions($product);
            $this->addPrices($product);
            $this->addUrls($product);
            $this->addKeywords($product);
            $this->addBonuses($product);
            $this->addSalesFrequencies($product);
            $this->addDateAddeds($product);
            $this->addSorts($product);
            $this->addUserGroups($product);
            $this->addImages($product);
            $this->addAttributes($product);
            $this->addProperties($product);

            $items[] = $this->item;
        }

        return $exporter->serializeItems($items, 0, 2, 2);
    }

    private function addOrderNumbers($product): void
    {
        foreach ($product->orderNumbers as $userGroup => $orderNumbers) {
            foreach ($orderNumbers as $orderNumber) {
                $this->item->addOrdernumber(new Ordernumber($orderNumber, $userGroup));
            }
        }
    }

    private function addNames($product): void
    {
        foreach ($product->names as $userGroup => $name) {
            $this->item->addName($name, $userGroup);
        }
    }

    private function addSummaries($product): void
    {
        foreach ($product->summaries as $userGroup => $summary) {
            $this->item->addSummary($summary, $userGroup);
        }
    }

    private function addDescriptions($product): void
    {
        foreach ($product->descriptions as $userGroup => $description) {
            $this->item->addDescription($description, $userGroup);
        }
    }

    private function addPrices($product): void
    {
        foreach ($product->prices as $userGroup => $price) {
            $this->item->addPrice($price, $userGroup);
        }
    }

    private function addUrls($product): void
    {
        foreach ($product->urls as $userGroup => $url) {
            $this->item->addUrl($url, $userGroup);
        }
    }

    private function addKeywords($product): void
    {
        foreach ($product->keywords as $userGroup => $keywords) {
            foreach ($keywords as $keyword) {
                $this->item->addKeyword(new Keyword($keyword, $userGroup));
            }
        }
    }

    private function addBonuses($product): void
    {
        foreach ($product->bonuses as $userGroup => $bonus) {
            $this->item->addBonus($bonus, $userGroup);
        }
    }

    private function addSalesFrequencies($product): void
    {
        foreach ($product->salesFrequencies as $userGroup => $salesFrequency) {
            $this->item->addSalesFrequency($salesFrequency, $userGroup);
        }
    }

    private function addDateAddeds($product): void
    {
        foreach ($product->dateAddeds as $userGroup => $dateAdded) {
            $this->item->addDateAdded(new DateTime($dateAdded), $userGroup);
        }
    }

    private function addSorts($product): void
    {
        foreach ($product->sorts as $userGroup => $sort) {
            $this->item->addSort($sort, $userGroup);
        }
    }

    private function addUserGroups($product): void
    {
        foreach ($product->userGroups as $userGroup) {
            $this->item->addUsergroup(new Usergroup($userGroup));
        }
    }

    private function addImages($product): void
    {
        foreach ($product->images as $userGroup => $images) {
            foreach ($images as $image => $type) {
                $this->item->addImage(new Image($image, $type, $userGroup));
            }
        }
    }

    private function addAttributes($product): void
    {
        foreach ($product->attributes as $attributeName => $attributeValues) {
            $this->item->addAttribute(new Attribute($attributeName, $attributeValues));
        }
    }

    private function addProperties($product): void
    {
        foreach ($product->properties as $propertyName => $values) {
            $propertyElement = new Property($propertyName, $values);
            $this->item->addProperty($propertyElement);
        }
    }
}

$example = new XmlExample();

// Output the XML content.
echo $example->createExport();
