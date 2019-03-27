<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DateTime;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds a CSV export inspired by the FINDOLOGIC documentation, which can be found at
 * https://docs.findologic.com/doku.php?id=export_patterns:csv .
 */
class CsvExample
{
    public function createExport(): string
    {
        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, [
            'sale', 'novelty', 'logo', 'availability', 'old_price', 'Basic_rate_price'
        ]);

        $item = $exporter->createItem('01120c948ad41a2284ad9f0402fbc7d');

        $this->addOrdernumbers($item);
        $this->addNames($item);
        $this->addSummaries($item);
        $this->addDescriptions($item);
        $this->addPrices($item);
        $this->addUrls($item);
        $this->addKeywords($item);
        $this->addBonuses($item);
        $this->addSalesFrequencies($item);
        $this->addDateAddeds($item);
        $this->addSorts($item);
        $this->addUsergroups($item);
        $this->addImages($item);
        $this->addAttributes($item);
        $this->addProperties($item);

        return $exporter->serializeItems([$item], 0, 1, 1);
    }

    private function addAttributes(Item $item): void
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

    private function addBonuses(Item $item): void
    {
        $item->addBonus(3);
    }

    private function addDateAddeds(Item $item): void
    {
        $item->addDateAdded(new DateTime());
    }

    private function addDescriptions(Item $item): void
    {
        $item->addDescription('With this sneaker you will walk in style. It\'s available in green and blue.');
    }

    private function addOrdernumbers(Item $item): void
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

    private function addImages(Item $item): void
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

    private function addKeywords(Item $item): void
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

    private function addNames(Item $item): void
    {
        $item->addName('Adidas Sneaker');
    }

    private function addPrices(Item $item): void
    {
        $item->addPrice(44.8);
        $item->setInsteadPrice(50);
        $item->setMaxPrice(47);
        $item->setTaxRate(20);
    }

    private function addProperties(Item $item): void
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

    private function addSalesFrequencies(Item $item): void
    {
        $item->addSalesFrequency(5);
    }

    private function addSorts(Item $item): void
    {
        $item->addSort(5);
    }

    private function addSummaries(Item $item): void
    {
        $item->addSummary('A cool and fashionable sneaker');
    }

    private function addUrls(Item $item): void
    {
        $item->addUrl('https://www.store.com/sneakers/adidas.html');
    }

    private function addUsergroups(Item $item): void
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
