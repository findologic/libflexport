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
 * This example class builds a xml export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link https://docs.findologic.com/doku.php?id=export_patterns:xml#example_xml
 */
class XmlExample
{
    public function createExport(): string
    {
        $exporter = Exporter::create(Exporter::TYPE_XML);

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
        $item->addBonus(5, 'LNrLF7BRVJ0toQ==');
    }

    private function addDateAddeds(Item $item): void
    {
        $item->addDateAdded(new DateTime());
        $item->addDateAdded(new DateTime(), 'LNrLF7BRVJ0toQ==');
    }

    private function addDescriptions(Item $item): void
    {
        $item->addDescription('With this sneaker you will walk in style. It\'s available in green and blue.');
        $item->addDescription(
            'With this men\'s sneaker you will walk in style. It\'s comes in various sizes and colors.',
            'LNrLF7BRVJ0toQ=='
        );
    }

    private function addOrdernumbers(Item $item): void
    {
        $ordernumbersData = [
            '' => [
                '277KTL',
                '4987123846879'
            ],
            'LNrLF7BRVJ0toQ==' => [
                '377KTL'
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
                'https://www.store.com/images/277KTL.png' => Image::TYPE_DEFAULT,
                'https://www.store.com/images/thumbnails/277KTL.png' => Image::TYPE_THUMBNAIL
            ],
            'LNrLF7BRVJ0toQ==' => [
                'https://www.store.com/images/277KTLmen.png' => Image::TYPE_DEFAULT,
                'https://www.store.com/images/thumbnails/277KTLmen.png' => Image::TYPE_THUMBNAIL
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
            ],
            'LNrLF7BRVJ0toQ==' => [
                '377KTL'
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
        $item->addName('Adidas Men\'s Sneaker', 'LNrLF7BRVJ0toQ==');
    }

    private function addPrices(Item $item): void
    {
        $item->addPrice(44.8);
        $item->addPrice(45.9, 'LNrLF7BRVJ0toQ==');
    }

    private function addProperties(Item $item): void
    {
        $propertiesData = [
            'sale' => [
                '' => 1,
                'LNrLF7BRVJ0toQ==' => 0
            ],
            'novelty' => [
                '' => 0,
                'LNrLF7BRVJ0toQ==' => 0
            ],
            'logo' => [
                '' => 'http://www.shop.de/brand.png',
                'LNrLF7BRVJ0toQ==' => 'http://www.shop.de/brand.png'
            ],
            'availability' => [
                '' => '<span style="color: green;">4 days</span>',
                'LNrLF7BRVJ0toQ==' => '<span style="color: green;">3 days</span>'
            ],
            'old_price' => [
                '' => 99.9,
                'LNrLF7BRVJ0toQ==' => 99.9
            ],
            'Basic_rate_price' => [
                '' => 99.9,
                'LNrLF7BRVJ0toQ==' => 89.9
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
        $item->addSalesFrequency(10, 'LNrLF7BRVJ0toQ==');
    }

    private function addSorts(Item $item): void
    {
        $item->addSort(5);
        $item->addSort(7, 'LNrLF7BRVJ0toQ==');
    }

    private function addSummaries(Item $item): void
    {
        $item->addSummary('A cool and fashionable sneaker');
        $item->addSummary('A cool and fashionable sneaker for men', 'LNrLF7BRVJ0toQ==');
    }

    private function addUrls(Item $item): void
    {
        $item->addUrl('https://www.store.com/sneakers/adidas.html');
        $item->addUrl('https://www.store.com/sneakers/mens/adidas.html', 'LNrLF7BRVJ0toQ==');
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

$example = new XmlExample();

// Output the XML content.
echo $example->createExport();
