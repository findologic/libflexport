<?php

namespace FINDOLOGIC\Export\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use DateTime;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Examples\Data\ExampleBaseItem;
use FINDOLOGIC\Export\Examples\Data\ExampleContentItem;
use FINDOLOGIC\Export\Examples\Data\ExampleProductItem;
use FINDOLOGIC\Export\Exporter;

abstract class BaseExample
{
    protected Item $item;

    /** @var ExampleBaseItem[] */
    protected array $products = [];

    public function __construct()
    {
        $this->products[] = new ExampleProductItem();
        $this->products[] = new ExampleContentItem();
    }

    /**
     * @return Item[]
     */
    protected function buildItems(Exporter $exporter): array
    {
        $items = [];

        foreach ($this->products as $product) {
            $this->item = $exporter->createItem($product->id);

            $this->addOrderNumbers($product);
            $this->addNames($product);
            $this->addSummaries($product);
            $this->addDescriptions($product);
            $this->addPrices($product);
            $this->addOverriddenPrice($product);
            $this->addUrls($product);
            $this->addKeywords($product);
            $this->addBonuses($product);
            $this->addSalesFrequencies($product);
            $this->addDateAddeds($product);
            $this->addSorts($product);
            $this->addVisibilities($product);
            $this->addGroups($product);
            $this->addImages($product);
            $this->addAttributes($product);
            $this->addProperties($product);
            $this->addVariants($exporter, $product);

            $items[] = $this->item;
        }

        return $items;
    }

    protected function addOrderNumbers(ExampleBaseItem $product): void
    {
        foreach ($product->orderNumbers as $userGroup => $orderNumbers) {
            foreach ($orderNumbers as $orderNumber) {
                $this->item->addOrdernumber(new Ordernumber($orderNumber, $userGroup));
            }
        }
    }

    protected function addNames(ExampleBaseItem $product): void
    {
        foreach ($product->names as $userGroup => $name) {
            $this->item->addName($name, $userGroup);
        }
    }

    protected function addSummaries(ExampleBaseItem $product): void
    {
        foreach ($product->summaries as $userGroup => $summary) {
            $this->item->addSummary($summary, $userGroup);
        }
    }

    protected function addDescriptions(ExampleBaseItem $product): void
    {
        foreach ($product->descriptions as $userGroup => $description) {
            $this->item->addDescription($description, $userGroup);
        }
    }

    protected function addPrices(ExampleBaseItem $product): void
    {
        foreach ($product->prices as $userGroup => $price) {
            $this->item->addPrice($price, $userGroup);
        }
    }

    protected function addOverriddenPrice(ExampleBaseItem $product): void
    {
        foreach ($product->overriddenPrices as $userGroup => $overriddenPrice) {
            $this->item->addOverriddenPrice($overriddenPrice, $userGroup);
        }
    }

    protected function addUrls(ExampleBaseItem $product): void
    {
        foreach ($product->urls as $userGroup => $url) {
            $this->item->addUrl($url, $userGroup);
        }
    }

    protected function addKeywords(ExampleBaseItem $product): void
    {
        foreach ($product->keywords as $userGroup => $keywords) {
            foreach ($keywords as $keyword) {
                $this->item->addKeyword(new Keyword($keyword, $userGroup));
            }
        }
    }

    protected function addBonuses(ExampleBaseItem $product): void
    {
        foreach ($product->bonuses as $userGroup => $bonus) {
            $this->item->addBonus($bonus, $userGroup);
        }
    }

    protected function addSalesFrequencies(ExampleBaseItem $product): void
    {
        foreach ($product->salesFrequencies as $userGroup => $salesFrequency) {
            $this->item->addSalesFrequency($salesFrequency, $userGroup);
        }
    }

    protected function addDateAddeds(ExampleBaseItem $product): void
    {
        foreach ($product->dateAddeds as $userGroup => $dateAdded) {
            $this->item->addDateAdded(new DateTime($dateAdded), $userGroup);
        }
    }

    protected function addSorts(ExampleBaseItem $product): void
    {
        foreach ($product->sorts as $userGroup => $sort) {
            $this->item->addSort($sort, $userGroup);
        }
    }

    protected function addVisibilities(ExampleBaseItem $product): void
    {
        foreach ($product->visibilities as $userGroup => $visible) {
            $this->item->addVisibility($visible, $userGroup);
        }
    }

    protected function addGroups(ExampleBaseItem $product): void
    {
        foreach ($product->groups as $group) {
            $this->item->addGroup(new Group($group));
        }
    }

    protected function addImages(ExampleBaseItem $product): void
    {
        foreach ($product->images as $userGroup => $images) {
            foreach ($images as $image => $type) {
                $this->item->addImage(new Image($image, $type, $userGroup));
            }
        }
    }

    protected function addAttributes(ExampleBaseItem $product): void
    {
        foreach ($product->attributes as $attributeName => $attributeValues) {
            $this->item->addAttribute(new Attribute($attributeName, $attributeValues));
        }
    }

    protected function addProperties(ExampleBaseItem $product): void
    {
        foreach ($product->properties as $propertyName => $values) {
            if ($propertyName === 'variants') {
                foreach ($values as $userGroup => $value) {
                    $values[$userGroup] = json_encode($value);
                }
            }

            $propertyElement = new Property($propertyName, $values);
            $this->item->addProperty($propertyElement);
        }
    }

    protected function addVariants(Exporter $exporter, ExampleBaseItem $product): void
    {
        foreach ($product->variants as $variantAttribute => $values) {
            $variant = $exporter->createVariant($values['id'], $product->id);

            $variant->addName($values['title']);
            $variant->addOrdernumber(new Ordernumber($values['ordernumber']));
            $variant->addPrice($values['price']);
            $variant->addMergedAttribute(
                new Attribute('variant_value', [$variantAttribute])
            );

            $variant->addProperty(
                new Property('badge', ['' => $values['badge']])
            );
            if ($values['sale']) {
                $variant->addProperty(
                    new Property('sale', ['' => $values['sale']])
                );
            }

            if ($values['overridden_price']) {
                $variant->addOverriddenPrice($values['overridden_price']);
            }

            $this->item->addVariant($variant);
        }
    }

    abstract public function createExport(): string;
}
