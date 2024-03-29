<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Examples\Data;

use FINDOLOGIC\Export\Enums\ImageType;

/*
 * This class represents an example content.
 * As this is just a static class, an own logic must be implemented
 */
class ExampleContentItem extends ExampleBaseItem
{
    public string $id = 'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra';

    public array $orderNumbers = [
        self::DEFAULT_USER_GROUP => [
            'pdf304xyz',
            '9424585418519'
        ],
        self::SPECIFIC_USER_GROUP => [
            'pdf305xyz'
        ]
    ];

    public array $names = [
        self::DEFAULT_USER_GROUP => 'Aliquam eget vehicula.'
    ];

    public array $summaries = [
        self::DEFAULT_USER_GROUP => 'Nullam blandit in ipsum ac feugiat. Vivamus varius, velit nec.'
    ];

    public array $descriptions = [
        self::DEFAULT_USER_GROUP =>
            'In tempus eleifend orci, eu suscipit dolor pellentesque ac. Morbi.'
    ];

    public array $prices = [
        self::DEFAULT_USER_GROUP => 0
    ];

    public array $overriddenPrices = [
        self::DEFAULT_USER_GROUP => 0
    ];

    public array $urls = [
        self::DEFAULT_USER_GROUP => 'https://www.store.com/documents/pdf304xyz.pdf',
        self::SPECIFIC_USER_GROUP => 'https://www.store.com/documents/pdf305xyz.pdf',
    ];

    public array $keywords = [
        self::DEFAULT_USER_GROUP => [
            'pdf304xyz',
            '9424585418519'
        ],
        self::SPECIFIC_USER_GROUP => [
            'pdf305xyz'
        ]
    ];

    public array $bonuses = [
        self::DEFAULT_USER_GROUP => 7
    ];

    public array $salesFrequencies = [
        self::DEFAULT_USER_GROUP => 23
    ];

    public array $dateAddeds = [
        self::DEFAULT_USER_GROUP => '2019-10-31T10:20:28+02:00'
    ];

    public array $sorts = [
        self::DEFAULT_USER_GROUP => 1
    ];

    public array $groups = [
        self::SPECIFIC_USER_GROUP,
        'student'
    ];

    public array $images = [
        self::DEFAULT_USER_GROUP => [
            'https://www.store.com/images/pdf304xyz.png' => ImageType::DEFAULT,
            'https://www.store.com/images/thumbnails/pdf304xyz.png' => ImageType::THUMBNAIL
        ],
        self::SPECIFIC_USER_GROUP => [
            'https://www.store.com/images/pdf305xyz.png' => ImageType::DEFAULT,
            'https://www.store.com/images/thumbnails/pdf305xyz.png' => ImageType::THUMBNAIL
        ]
    ];

    public array $attributes = [
        'type' => [
            'content',
        ]
    ];

    public array $properties = [
        'file_type' => [
            self::DEFAULT_USER_GROUP => 'pdf',
        ],
        'number_of_comments' => [
            self::DEFAULT_USER_GROUP => 9,
        ]
    ];

    public array $variants = [];

    public array $visibilities = [];
}
