<?php

use FINDOLOGIC\Export\Data\Image;

/*
 * This class represents an example product.
 * As this is just a static class, an own logic must be implemented
 */

class ExampleProductItem extends ExampleBaseItem
{
    public string $id = '01120c948ad41a2284ad9f0402fbc7d';

    public array $orderNumbers = [
        self::DEFAULT_USER_GROUP => [
            '277KTL',
            '4987123846879'
        ],
        self::SPECIFIC_USER_GROUP => [
            '377KTL'
        ]
    ];

    public array $names = [
        self::DEFAULT_USER_GROUP => 'Adidas Sneaker',
        self::SPECIFIC_USER_GROUP => 'Adidas Men\'s Sneaker'
    ];

    public array $summaries = [
        self::DEFAULT_USER_GROUP => 'A cool and fashionable sneaker',
        self::SPECIFIC_USER_GROUP => 'A cool and fashionable sneaker for men'
    ];

    public array $descriptions = [
        self::DEFAULT_USER_GROUP =>
            'With this sneaker you will walk in style. It\'s available in green and blue.',
        self::SPECIFIC_USER_GROUP =>
            'With this men\'s sneaker you will walk in style. It\'s comes in various sizes and colors.'
    ];

    public array $prices = [
        self::DEFAULT_USER_GROUP => 44.8,
        self::SPECIFIC_USER_GROUP => 45.9
    ];

    public array $overriddenPrices = [
        self::DEFAULT_USER_GROUP => 54.8,
        self::SPECIFIC_USER_GROUP => 55.9
    ];

    public array $urls = [
        self::DEFAULT_USER_GROUP => 'https://www.store.com/sneakers/adidas.html',
        self::SPECIFIC_USER_GROUP => 'https://www.store.com/sneakers/adidas.html'
    ];

    public array $keywords = [
        self::DEFAULT_USER_GROUP => [
            '277KTL',
            '4987123846879'
        ],
        self::SPECIFIC_USER_GROUP => [
            '377KTL'
        ]
    ];

    public array $bonuses = [
        self::DEFAULT_USER_GROUP => 3,
        self::SPECIFIC_USER_GROUP => 5
    ];

    public array $salesFrequencies = [
        self::DEFAULT_USER_GROUP => 5,
        self::SPECIFIC_USER_GROUP => 10
    ];

    public array $dateAddeds = [
        self::DEFAULT_USER_GROUP => '2019-10-31T10:20:28+02:00',
        self::SPECIFIC_USER_GROUP => '2019-10-31T10:20:28+02:00'
    ];

    public array $sorts = [
        self::DEFAULT_USER_GROUP => 5,
        self::SPECIFIC_USER_GROUP => 7
    ];

    public array $groups = [
        self::SPECIFIC_USER_GROUP,
        'student'
    ];

    public array $images = [
        self::DEFAULT_USER_GROUP => [
            'https://www.store.com/images/277KTL.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/277KTL.png' => Image::TYPE_THUMBNAIL
        ],
        self::SPECIFIC_USER_GROUP => [
            'https://www.store.com/images/277KTLmen.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/277KTLmen.png' => Image::TYPE_THUMBNAIL
        ]
    ];

    public array $attributes = [
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
            'green,brown',
            'blue'
        ],
        'type' => [
            'product',
        ]
    ];

    public array $properties = [
        'sale' => [
            self::DEFAULT_USER_GROUP => 1,
            self::SPECIFIC_USER_GROUP => 0
        ],
        'novelty' => [
            self::DEFAULT_USER_GROUP => 0,
            self::SPECIFIC_USER_GROUP => 0
        ],
        'logo' => [
            self::DEFAULT_USER_GROUP => 'http://www.shop.de/brand.png',
            self::SPECIFIC_USER_GROUP => 'http://www.shop.de/brand.png'
        ],
        'availability' => [
            self::DEFAULT_USER_GROUP => '<span style="color: green;">4 days</span>',
            self::SPECIFIC_USER_GROUP => '<span style="color: green;">3 days</span>'
        ],
        'old_price' => [
            self::DEFAULT_USER_GROUP => 99.9,
            self::SPECIFIC_USER_GROUP => 99.9
        ],
        'Basic_rate_price' => [
            self::DEFAULT_USER_GROUP => 99.9,
            self::SPECIFIC_USER_GROUP => 89.9
        ],
        'variants' => [
            self::DEFAULT_USER_GROUP => [
                'Blue' => [
                    'title' => 'Adidas Sneaker blue',
                    'badge' => 'https://www.store.com/images/badges/new.png',
                    'price' => '13.99',
                    'old_price' => '',
                    'sale' => '',
                    'image' => 'https://www.store.com/images/277KTL-blue.png',
                    'thumbnail' => 'https://www.store.com/images/thumbs/277KTL-blue.png',
                    'productUrl' => 'https://www.store.com/sneakers/adidas-blue.html'
                ],
                'Red' => [
                    'title' => 'Adidas Sneaker red',
                    'badge' => 'https://www.store.com/images/badges/sale.png',
                    'price' => '7.49',
                    'old_price' => '14.99',
                    'sale' => '50%',
                    'image' => 'https://www.store.com/images/277KTL-red.png',
                    'thumbnail' => 'https://www.store.com/images/thumbs/277KTL-red.png',
                    'productUrl' => 'https://www.store.com/sneakers/adidas-red.html'
                ],
                'Grey' => [
                    'title' => 'Adidas Sneaker grey',
                    'badge' => 'https://www.store.com/images/badges/sale.png',
                    'price' => '6.49',
                    'old_price' => '12.99',
                    'sale' => '50%',
                    'thumbnail' => 'https://www.store.com/images/thumbs/277KTL-grey.png',
                    'productUrl' => 'https://www.store.com/sneakers/adidas-grey.html'
                ]
            ]
        ]
    ];

    public array $variants = [];

    public array $visibilities = [
        self::DEFAULT_USER_GROUP => 1,
        self::SPECIFIC_USER_GROUP => false
    ];
}
