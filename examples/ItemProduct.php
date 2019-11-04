<?php

use FINDOLOGIC\Export\Data\Image;

class ItemProduct
{
    const defaultUserGroup = '';
    const specificUserGroup = 'LNrLF7BRVJ0toQ==';
    
    public $id = '01120c948ad41a2284ad9f0402fbc7d';

    public $orderNumbers = [
        self::defaultUserGroup => [
            '277KTL',
            '4987123846879'
        ],
        self::specificUserGroup => [
            '377KTL'
        ]
    ];

    public $names = [
        self::defaultUserGroup => 'Adidas Sneaker',
        self::specificUserGroup => 'Adidas Men\'s Sneaker'
    ];

    public $summaries = [
        self::defaultUserGroup => 'A cool and fashionable sneaker',
        self::specificUserGroup => 'A cool and fashionable sneaker for men'
    ];

    public $descriptions = [
        self::defaultUserGroup =>
            'With this sneaker you will walk in style. It\'s available in green and blue.',
        self::specificUserGroup =>
            'With this men\'s sneaker you will walk in style. It\'s comes in various sizes and colors.'
    ];

    public $prices = [
        self::defaultUserGroup => 44.8,
        self::specificUserGroup => 45.9
    ];

    public $urls = [
        self::defaultUserGroup => 'https://www.store.com/sneakers/adidas.html',
        self::specificUserGroup => 'https://www.store.com/sneakers/adidas.html'
    ];

    public $keywords = [
        self::defaultUserGroup => [
            '277KTL',
            '4987123846879'
        ],
        self::specificUserGroup => [
            '377KTL'
        ]
    ];

    public $bonuses = [
        self::defaultUserGroup => 3,
        self::specificUserGroup => 5
    ];

    public $salesFrequencies = [
        self::defaultUserGroup => 5,
        self::specificUserGroup => 10
    ];

    public $dateAddeds = [
        self::defaultUserGroup => '2019-10-31T10:20:28+02:00',
        self::specificUserGroup => '2019-10-31T10:20:28+02:00'
    ];

    public $sorts = [
        self::defaultUserGroup => 5,
        self::specificUserGroup => 7
    ];

    public $userGroups = [
        self::specificUserGroup,
        'cHBw'
    ];

    public $images = [
        self::defaultUserGroup => [
            'https://www.store.com/images/277KTL.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/277KTL.png' => Image::TYPE_THUMBNAIL
        ],
        self::specificUserGroup => [
            'https://www.store.com/images/277KTLmen.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/277KTLmen.png' => Image::TYPE_THUMBNAIL
        ]
    ];

    public $attributes = [
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
    ];

    public $properties = [
        'sale' => [
            self::defaultUserGroup => 1,
            self::specificUserGroup => 0
        ],
        'novelty' => [
            self::defaultUserGroup => 0,
            self::specificUserGroup => 0
        ],
        'logo' => [
            self::defaultUserGroup => 'http://www.shop.de/brand.png',
            self::specificUserGroup => 'http://www.shop.de/brand.png'
        ],
        'availability' => [
            self::defaultUserGroup => '<span style="color: green;">4 days</span>',
            self::specificUserGroup => '<span style="color: green;">3 days</span>'
        ],
        'old_price' => [
            self::defaultUserGroup => 99.9,
            self::specificUserGroup => 99.9
        ],
        'Basic_rate_price' => [
            self::defaultUserGroup => 99.9,
            self::specificUserGroup => 89.9
        ],
        'variants' => [
            self::defaultUserGroup => '{
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
        ]
    ];
}