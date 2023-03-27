<?php

namespace FINDOLOGIC\Export\Examples\Data;

use FINDOLOGIC\Export\Enums\ImageType;

/*
 * This class represents an example product.
 * As this is just a static class, an own logic must be implemented
 */
final class ExampleProductItemWithVariants extends ExampleProductItem
{
    public string $id = '5444bb0aa92841858ac47ef71c9cbab9';

    public array $orderNumbers = [
        self::DEFAULT_USER_GROUP => [
            '277KTL-v',
            '4987123846879-v'
        ],
    ];

    public array $names = [
        self::DEFAULT_USER_GROUP => 'Adidas Sneaker',
    ];

    public array $summaries = [
        self::DEFAULT_USER_GROUP => 'A cool and fashionable sneaker',
    ];

    public array $descriptions = [
        self::DEFAULT_USER_GROUP =>
            "With this sneaker you will walk in style. It's available in green and blue.",
    ];

    public array $prices = [
        self::DEFAULT_USER_GROUP => 44.8,
    ];

    public array $overriddenPrices = [
        self::DEFAULT_USER_GROUP => 54.8,
    ];

    public array $urls = [
        self::DEFAULT_USER_GROUP => 'https://www.store.com/sneakers/adidas.html',
    ];

    public array $keywords = [
        self::DEFAULT_USER_GROUP => [
            '277KTL',
            '4987123846879'
        ],
    ];

    public array $bonuses = [
        self::DEFAULT_USER_GROUP => 3,
    ];

    public array $salesFrequencies = [
        self::DEFAULT_USER_GROUP => 5,
    ];

    public array $dateAddeds = [
        self::DEFAULT_USER_GROUP => '2019-10-31T10:20:28+02:00',
    ];

    public array $sorts = [
        self::DEFAULT_USER_GROUP => 5,
    ];

    public array $groups = [
        self::SPECIFIC_USER_GROUP,
        'student'
    ];

    public array $images = [
        self::DEFAULT_USER_GROUP => [
            'https://www.store.com/images/277KTL.png' => ImageType::DEFAULT,
            'https://www.store.com/images/thumbnails/277KTL.png' => ImageType::THUMBNAIL
        ],
    ];

    public array $properties = [
        'sale' => [
            self::DEFAULT_USER_GROUP => 1,
        ],
        'novelty' => [
            self::DEFAULT_USER_GROUP => 0,
        ],
        'logo' => [
            self::DEFAULT_USER_GROUP => 'http://www.shop.de/brand.png',
        ],
        'availability' => [
            self::DEFAULT_USER_GROUP => '<span style="color: green;">4 days</span>',
        ],
        'old_price' => [
            self::DEFAULT_USER_GROUP => 99.9,
        ],
        'Basic_rate_price' => [
            self::DEFAULT_USER_GROUP => 99.9,
        ],
    ];

    public array $variants = [
        'Blue' => [
            'id' => 'variant1',
            'ordernumber' => 'variant1',
            'title' => 'Adidas Sneaker blue',
            'badge' => 'https://www.store.com/images/badges/new.png',
            'price' => '13.99',
            'overridden_price' => '',
            'sale' => '',
            'groups' => ['group1', 'group2']
        ],
        'Red' => [
            'id' => 'variant2',
            'ordernumber' => 'variant2',
            'title' => 'Adidas Sneaker red',
            'badge' => 'https://www.store.com/images/badges/sale.png',
            'price' => '7.49',
            'overridden_price' => '14.99',
            'sale' => '50%',
            'groups' => ['group1', 'group2']
        ],
        'Grey' => [
            'id' => 'variant3',
            'ordernumber' => 'variant3',
            'title' => 'Adidas Sneaker grey',
            'badge' => 'https://www.store.com/images/badges/sale.png',
            'price' => '6.49',
            'overridden_price' => '12.99',
            'sale' => '50%',
            'groups' => ['group1', 'group2']
        ]
    ];

    public array $visibilities = [
        self::DEFAULT_USER_GROUP => '1'
    ];
}
