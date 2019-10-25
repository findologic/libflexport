<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

        //type: product
        $items[] = $exporter->createItem('01120c948ad41a2284ad9f0402fbc7d');
        //type: content
        $items[] = $exporter->createItem('content_ypy44hn5rpk8nggba8vxmpx68d8v7ra');

        foreach ($items as $item) {
            $this->addOrderNumbers($item);
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
            $this->addUserGroups($item);
            $this->addImages($item);
            $this->addAttributes($item);
            $this->addProperties($item);
        }

        return $exporter->serializeItems($items, 0, 2, 2);
    }

    private function addAttributes(Item $item): void
    {
        $attributesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
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
                ],
                'type' => [
                    'product',
                ]
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                'type' => [
                    'content',
                ]
            ]
        ];

        foreach ($attributesData[$item->getId()] as $attributeName => $attributeValues) {
            $item->addAttribute(new Attribute($attributeName, $attributeValues));
        }
    }

    private function addBonuses(Item $item): void
    {
        $bonusesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 3,
                'LNrLF7BRVJ0toQ==' => 5
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 7,
            ]
        ];

        foreach ($bonusesData[$item->getId()] as $userGroup => $bonus) {
            $item->addBonus($bonus, $userGroup);
        }
    }

    private function addDateAddeds(Item $item): void
    {
        $dateAddedsData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => new DateTime(),
                'LNrLF7BRVJ0toQ==' => new DateTime()
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => new DateTime()
            ]
        ];

        foreach ($dateAddedsData[$item->getId()] as $userGroup => $dateAdded) {
            $item->addDateAdded($dateAdded, $userGroup);
        }
    }

    private function addDescriptions(Item $item): void
    {
        $descriptionsData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' =>
                    'With this sneaker you will walk in style. It\'s available in green and blue.',
                'LNrLF7BRVJ0toQ==' =>
                    'With this men\'s sneaker you will walk in style. It\'s comes in various sizes and colors.'
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' =>
                    'In tempus eleifend orci, eu suscipit dolor pellentesque ac. Morbi.'
            ]
        ];

        foreach ($descriptionsData[$item->getId()] as $userGroup => $description) {
            $item->addDescription($description, $userGroup);
        }
    }

    private function addOrderNumbers(Item $item): void
    {
        $orderNumbersData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => [
                    '277KTL',
                    '4987123846879'
                ],
                'LNrLF7BRVJ0toQ==' => [
                    '377KTL'
                ]
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => [
                    'pdf304xyz',
                    '9424585418519'
                ],
                'LNrLF7BRVJ0toQ==' => [
                    'pdf305xyz'
                ]
            ]
        ];

        foreach ($orderNumbersData[$item->getId()] as $userGroup => $orderNumbers) {
            foreach ($orderNumbers as $orderNumber) {
                $item->addOrdernumber(new Ordernumber($orderNumber, $userGroup));
            }
        }
    }

    private function addImages(Item $item): void
    {
        $imagesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => [
                    'https://www.store.com/images/277KTL.png' => Image::TYPE_DEFAULT,
                    'https://www.store.com/images/thumbnails/277KTL.png' => Image::TYPE_THUMBNAIL
                ],
                'LNrLF7BRVJ0toQ==' => [
                    'https://www.store.com/images/277KTLmen.png' => Image::TYPE_DEFAULT,
                    'https://www.store.com/images/thumbnails/277KTLmen.png' => Image::TYPE_THUMBNAIL
                ]
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => [
                    'https://www.store.com/images/pdf304xyz.png' => Image::TYPE_DEFAULT,
                    'https://www.store.com/images/thumbnails/pdf304xyz.png' => Image::TYPE_THUMBNAIL
                ],
                'LNrLF7BRVJ0toQ==' => [
                    'https://www.store.com/images/pdf305xyz.png' => Image::TYPE_DEFAULT,
                    'https://www.store.com/images/thumbnails/pdf305xyz.png' => Image::TYPE_THUMBNAIL
                ]
            ]
        ];

        foreach ($imagesData[$item->getId()] as $userGroup => $images) {
            foreach ($images as $image => $type) {
                $item->addImage(new Image($image, $type, $userGroup));
            }
        }
    }

    private function addKeywords(Item $item): void
    {
        $keywordsData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => [
                    '277KTL',
                    '4987123846879'
                ],
                'LNrLF7BRVJ0toQ==' => [
                    '377KTL'
                ]
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => [
                    'pdf304xyz',
                    '9424585418519'
                ],
                'LNrLF7BRVJ0toQ==' => [
                    'pdf305xyz'
                ]
            ]
        ];

        foreach ($keywordsData[$item->getId()] as $userGroup => $keywords) {
            foreach ($keywords as $keyword) {
                $item->addKeyword(new Keyword($keyword, $userGroup));
            }
        }
    }

    private function addNames(Item $item): void
    {
        $namesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 'Adidas Sneaker',
                'LNrLF7BRVJ0toQ==' => 'Adidas Men\'s Sneaker'
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 'Aliquam eget vehicula.'
            ]
        ];

        foreach ($namesData[$item->getId()] as $userGroup => $name) {
            $item->addName($name, $userGroup);
        }
    }

    private function addPrices(Item $item): void
    {
        $pricesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 44.8,
                'LNrLF7BRVJ0toQ==' => 45.9
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 0
            ]
        ];

        foreach ($pricesData[$item->getId()] as $userGroup => $price) {
            $item->addPrice($price, $userGroup);
        }
    }

    private function addProperties(Item $item): void
    {
        $propertiesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
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
                ],
                'variants' => [
                    '' => '{
                        "Blue" : {
                            "title": "Adidas Sneaker blue",
                            "badge": "https://www.store.com/images/badges/new.png",
                            "price": "13.99",
                            "old_price": "",
                            "sale": "",
                            "image": "https://www.store.com/images/277KTL-blue.png",
                            "thumbnail": "https://www.store.com/images/thumbs/277KTL-blue.png"
                            "productUrl": "https://www.store.com/sneakers/adidas-blue.html" 
                        }, 
                        "Red" : { 
                            "title": "Adidas Sneaker red",
                            "badge": "https://www.store.com/images/badges/sale.png",
                            "price": "7.49",
                            "old_price": "14.99",
                            "sale": "50%",
                            "image": "https://www.store.com/images/277KTL-red.png",
                            "thumbnail": "https://www.store.com/images/thumbs/277KTL-red.png"
                            "productUrl": "https://www.store.com/sneakers/adidas-red.html" 
                        },
                        "Grey" : { 
                            "title": "Adidas Sneaker grey",
                            "badge": "https://www.store.com/images/badges/sale.png",
                            "price": "6.49",
                            "old_price": "12.99",
                            "sale": "50%",
                            "thumbnail": "https://www.store.com/images/thumbs/277KTL-grey.png"
                            "productUrl": "https://www.store.com/sneakers/adidas-grey.html" 
                        }
                    }'
                ],
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                'file_type' => [
                    '' => 'pdf',
                ],
                'number_of_comments' => [
                    '' => 9,
                ]
            ]
        ];

        foreach ($propertiesData[$item->getId()] as $propertyName => $values) {
            $propertyElement = new Property($propertyName, $values);
            $item->addProperty($propertyElement);
        }
    }

    private function addSalesFrequencies(Item $item): void
    {
        $salesFrequenciesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 5,
                'LNrLF7BRVJ0toQ==' => 10
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 23
            ]
        ];

        foreach ($salesFrequenciesData[$item->getId()] as $userGroup => $salesFrequency) {
            $item->addSalesFrequency($salesFrequency, $userGroup);
        }
    }

    private function addSorts(Item $item): void
    {
        $sortsData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 5,
                'LNrLF7BRVJ0toQ==' => 7
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 1
            ]
        ];

        foreach ($sortsData[$item->getId()] as $userGroup => $sort) {
            $item->addSort($sort, $userGroup);
        }
    }

    private function addSummaries(Item $item): void
    {
        $summariesData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 'A cool and fashionable sneaker',
                'LNrLF7BRVJ0toQ==' => 'A cool and fashionable sneaker for men'
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 'Nullam blandit in ipsum ac feugiat. Vivamus varius, velit nec.'
            ]
        ];

        foreach ($summariesData[$item->getId()] as $userGroup => $summary) {
            $item->addSummary($summary, $userGroup);
        }
    }

    private function addUrls(Item $item): void
    {
        $urlsData = [
            '01120c948ad41a2284ad9f0402fbc7d' => [
                '' => 'https://www.store.com/sneakers/adidas.html',
                'LNrLF7BRVJ0toQ==' => 'https://www.store.com/sneakers/adidas.html'
            ],
            'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra' => [
                '' => 'https://www.store.com/documents/pdf304xyz.pdf',
                'LNrLF7BRVJ0toQ==' => 'https://www.store.com/documents/pdf305xyz.pdf',
            ]
        ];

        foreach ($urlsData[$item->getId()] as $userGroup => $url) {
            $item->addUrl($url, $userGroup);
        }
    }

    private function addUserGroups(Item $item): void
    {
        $userGroups = [
            'LNrLF7BRVJ0toQ==',
            'cHBw'
        ];

        foreach ($userGroups as $userGroup) {
            $item->addUsergroup(new Usergroup($userGroup));
        }
    }
}
