<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Data\Property;

/**
 * This example class builds a CSV export inspired by the FINDOLOGIC documentation, which can be found at
 * https://docs.findologic.com/doku.php?id=export_patterns:csv .
 */
class CsvExample
{
    public function createExport()
    {
        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, [
            'sale', 'novelty', 'logo', 'availability', 'old_price', 'Basic_rate_price'
        ]);

        $itemsToExport = [];

        // Here you could have an array with the item data to iterate threw
        $itemsData = ['item1'];

        foreach ($itemsData as $itemData) {
            $item = $exporter->createItem('01120c948ad41a2284ad9f0402fbc7d');

            $this->addOrdernumbers($item, $itemData);
            $this->addNames($item, $itemData);
            $this->addSummaries($item, $itemData);
            $this->addDescriptions($item, $itemData);
            $this->addPrices($item, $itemData);
            $this->addUrls($item, $itemData);
            $this->addKeywords($item, $itemData);
            $this->addBonuses($item, $itemData);
            $this->addSalesFrequencies($item, $itemData);
            $this->addDateAddeds($item, $itemData);
            $this->addSorts($item, $itemData);
            $this->addUsergroups($item, $itemData);
            $this->addImages($item, $itemData);
            $this->addAttributes($item, $itemData);
            $this->addProperties($item, $itemData);

            $itemsToExport[] = $item;
        }

        return $exporter->serializeItems($itemsToExport, 0, 1, 1);
    }

    private function addAttributes(Item $item, $itemData)
    {
        $attributesData = [
            'cat' => [
                'Sneakers_Men',
                'Specials_Sale'
            ],
            'cat_url' => [
                '/sneakers',
                '/sneakers/men',
                '/specials',
                '/specials/sale'
            ],
            'brand' => [
                'Adidas'
            ],
            'color' => [
                'green',
                'blue'
            ]
        ];

        foreach ($attributesData as $attributeName => $attributeValues) {
            $item->addAttribute(new Attribute($attributeName, $attributeValues));
        }
    }

    private function addBonuses(Item $item, $itemData)
    {
        $item->addBonus(3);
    }

    private function addDateAddeds(Item $item, $itemData)
    {
        $item->addDateAdded(new \DateTime());
    }

    private function addDescriptions(Item $item, $itemData)
    {
        $item->addDescription('With this sneaker you will walk in style. It\'s available in green and blue.');
    }

    private function addOrdernumbers(Item $item, $itemData)
    {
        $ordernumbersData = [
            '' => [
                '277KTL',
                '4987123846879'
            ]
        ];

        foreach ($ordernumbersData as $usergroup => $ordernumbers) {
            foreach ($ordernumbers as $ordernumber) {
                $item->addOrdernumber(new Ordernumber($ordernumber, $usergroup));
            }
        }
    }

    private function addImages(Item $item, $itemData)
    {
        $imagesData = [
            '' => [
                'https://www.store.com/images/277KTL.png' => Image::TYPE_DEFAULT
            ]
        ];

        foreach ($imagesData as $usergroup => $images) {
            foreach ($images as $image => $type) {
                $item->addImage(new Image($image, $type, $usergroup));
            }
        }
    }

    private function addKeywords(Item $item, $itemData)
    {
        $keywordsData = [
            '' => [
                '277KTL',
                '4987123846879'
            ]
        ];

        foreach ($keywordsData as $usergroup => $keywords) {
            foreach ($keywords as $keyword) {
                $item->addKeyword(new Keyword($keyword, $usergroup));
            }
        }
    }

    private function addNames(Item $item, $itemData)
    {
        $item->addName('Adidas Sneaker');
    }

    private function addPrices(Item $item, $itemData)
    {
        $item->addPrice(44.8);
        $item->setInsteadPrice(50);
        $item->setMaxPrice(47);
        $item->setTaxRate(20);
    }

    private function addProperties(Item $item, $itemData)
    {
        $propertiesData = [
            'sale' => [
                '' => 1
            ],
            'novelty' => [
                '' => 0
            ],
            'logo' => [
                '' => 'http://www.shop.de/brand.png'
            ],
            'availability' => [
                '' => '<span style="color: green;">4 days</span>'
            ],
            'old_price' => [
                '' => 99.9
            ],
            'Basic_rate_price' => [
                '' => 99.9
            ]
        ];

        foreach ($propertiesData as $propertyName => $values) {
            $propertyElement = new Property($propertyName, $values);
            $item->addProperty($propertyElement);
        }
    }

    private function addSalesFrequencies(Item $item, $itemData)
    {
        $item->addSalesFrequency(5);
    }

    private function addSorts(Item $item, $itemData)
    {
        $item->addSort(5);
    }

    private function addSummaries(Item $item, $itemData)
    {
        $item->addSummary('A cool and fashionable sneaker');
    }

    private function addUrls(Item $item, $itemData)
    {
        $item->addUrl('https://www.store.com/sneakers/adidas.html');
    }

    private function addUsergroups(Item $item, $itemData)
    {
        $usergroups = [
            'LNrLF7BRVJ0toQ==',
            'cHBw'
        ];

        foreach ($usergroups as $usergroup) {
            $item->addUsergroup(new Usergroup($usergroup));
        }
    }
}

$example = new CsvExample();

// Output the CSV content.
echo $example->createExport();
