<?php

require_once '../vendor/autoload.php';

use \FINDOLOGIC\Export\Exporter;
use \FINDOLOGIC\Export\Data\Ordernumber;
use \FINDOLOGIC\Export\Data\Name;
use \FINDOLOGIC\Export\Data\Summary;
use \FINDOLOGIC\Export\Data\Description;
use \FINDOLOGIC\Export\Data\Price;
use \FINDOLOGIC\Export\Data\Url;
use \FINDOLOGIC\Export\Data\Image;
use \FINDOLOGIC\Export\Data\Attribute;
use \FINDOLOGIC\Export\Data\Keyword;
use \FINDOLOGIC\Export\Data\Usergroup;
use \FINDOLOGIC\Export\Data\Bonus;
use \FINDOLOGIC\Export\Data\SalesFrequency;
use \FINDOLOGIC\Export\Data\DateAdded;
use \FINDOLOGIC\Export\Data\Sort;
use \FINDOLOGIC\Export\Data\Property;

/**
 * This example class builds a xml export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link https://docs.findologic.com/doku.php?id=export_patterns:xml#example_xml
 */
class XmlExample
{
    public function createExport()
    {
        $exporter = Exporter::create(Exporter::TYPE_XML);

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

    private function addAttributes($item, $itemData)
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

    private function addBonuses($item, $itemData)
    {
        $bonusesData = [
            '' => [
                3
            ],
            'LNrLF7BRVJ0toQ==' => [
                5
            ]
        ];

        $bonusElement = new Bonus();

        foreach ($bonusesData as $usergroup => $bonuses) {
            foreach ($bonuses as $bonus) {
                $bonusElement->setValue($bonus, $usergroup);
            }
        }

        $item->setBonus($bonusElement);
    }

    private function addDateAddeds($item, $itemData)
    {
        $dateAddedsData = [
            '' => [
                new \DateTime()
            ],
            'LNrLF7BRVJ0toQ==' => [
                new \DateTime()
            ]
        ];

        $dateAddedElement = new DateAdded();

        foreach ($dateAddedsData as $usergroup => $dateAddeds) {
            foreach ($dateAddeds as $dateAdded) {
                $dateAddedElement->setDateValue($dateAdded, $usergroup);
            }
        }

        $item->setDateAdded($dateAddedElement);
    }

    private function addDescriptions($item, $itemData)
    {
        $descriptionsData = [
            '' => [
                'With this sneaker you will walk in style. It\'s available in green and blue.'
            ],
            'LNrLF7BRVJ0toQ==' => [
                'With this men\'s sneaker you will walk in style. It\'s comes in various sizes and colors.'
            ]
        ];

        $descriptionElement = new Description();

        foreach ($descriptionsData as $usergroup => $descriptions) {
            foreach ($descriptions as $description) {
                $descriptionElement->setValue($description, $usergroup);
            }
        }

        $item->setDescription($descriptionElement);
    }

    private function addOrdernumbers($item, $itemData)
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

    private function addImages($item, $itemData)
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

    private function addKeywords($item, $itemData)
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

    private function addNames($item, $itemData)
    {
        $namesData = [
            '' => [
                'Adidas Sneaker'
            ],
            'LNrLF7BRVJ0toQ==' => [
                'Adidas Men\'s Sneaker'
            ]
        ];

        $nameElement = new Name();

        foreach ($namesData as $usergroup => $names) {
            foreach ($names as $name) {
                $nameElement->setValue($name, $usergroup);
            }
        }

        $item->setName($nameElement);
    }

    private function addPrices($item, $itemData)
    {
        $pricesData = [
            '' => [
                44.8
            ],
            'LNrLF7BRVJ0toQ==' => [
                45.9
            ]
        ];

        $priceElement = new Price();

        foreach ($pricesData as $usergroup => $prices) {
            foreach ($prices as $price) {
                $priceElement->setValue($price, $usergroup);
            }
        }

        $item->setPrice($priceElement);
    }

    private function addProperties($item, $itemData)
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

    private function addSalesFrequencies($item, $itemData)
    {
        $salesFrequenciesData = [
            '' => [
                5
            ],
            'LNrLF7BRVJ0toQ==' => [
                5
            ]
        ];

        $salesFrequencyElement = new SalesFrequency();

        foreach ($salesFrequenciesData as $usergroup => $salesFrequencies) {
            foreach ($salesFrequencies as $salesFrequency) {
                $salesFrequencyElement->setValue($salesFrequency, $usergroup);
            }
        }

        $item->setSalesFrequency($salesFrequencyElement);
    }

    private function addSorts($item, $itemData)
    {
        $sortsData = [
            '' => [
                5
            ],
            'LNrLF7BRVJ0toQ==' => [
                7
            ]
        ];

        $sortElement = new Sort();

        foreach ($sortsData as $usergroup => $sorts) {
            foreach ($sorts as $sort) {
                $sortElement->setValue($sort, $usergroup);
            }
        }

        $item->setSort($sortElement);
    }

    private function addSummaries($item, $itemData)
    {
        $summariesData = [
            '' => [
                'A cool and fashionable sneaker'
            ],
            'LNrLF7BRVJ0toQ==' => [
                'A cool and fashionable sneaker for men'
            ]
        ];

        $summaryElement = new Summary();

        foreach ($summariesData as $usergroup => $summaries) {
            foreach ($summaries as $summary) {
                $summaryElement->setValue($summary, $usergroup);
            }
        }

        $item->setSummary($summaryElement);
    }

    private function addUrls($item, $itemData)
    {
        $urlsData = [
            '' => [
                'https://www.store.com/sneakers/adidas.html'
            ],
            'LNrLF7BRVJ0toQ==' => [
                'https://www.store.com/sneakers/mens/adidas.html'
            ]
        ];

        $urlElement = new Url();

        foreach ($urlsData as $usergroup => $urls) {
            foreach ($urls as $url) {
                $urlElement->setValue($url, $usergroup);
            }
        }

        $item->setUrl($urlElement);
    }

    private function addUsergroups($item, $itemData)
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

// Echo the xml content
echo $example->createExport();


