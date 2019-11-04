<?php

use FINDOLOGIC\Export\Data\Image;

class ItemContent
{
    const defaultUserGroup = '';
    const specificUserGroup = 'LNrLF7BRVJ0toQ==';
    
    public $id = 'content_ypy44hn5rpk8nggba8vxmpx68d8v7ra';

    public $orderNumbers = [
        self::defaultUserGroup => [
            'pdf304xyz',
            '9424585418519'
        ],
        self::specificUserGroup => [
            'pdf305xyz'
        ]
    ];

    public $names = [
        self::defaultUserGroup => 'Aliquam eget vehicula.'
    ];

    public $summaries = [
        self::defaultUserGroup => 'Nullam blandit in ipsum ac feugiat. Vivamus varius, velit nec.'
    ];

    public $descriptions = [
        self::defaultUserGroup =>
            'In tempus eleifend orci, eu suscipit dolor pellentesque ac. Morbi.'
    ];

    public $prices = [
        self::defaultUserGroup => 0
    ];

    public $urls = [
        self::defaultUserGroup => 'https://www.store.com/documents/pdf304xyz.pdf',
        self::specificUserGroup => 'https://www.store.com/documents/pdf305xyz.pdf',
    ];

    public $keywords = [
        self::defaultUserGroup => [
            'pdf304xyz',
            '9424585418519'
        ],
        self::specificUserGroup => [
            'pdf305xyz'
        ]
    ];

    public $bonuses = [
        self::defaultUserGroup => 7
    ];

    public $salesFrequencies = [
        self::defaultUserGroup => 23
    ];

    public $dateAddeds = [
        self::defaultUserGroup => '2019-10-31T10:20:28+02:00'
    ];

    public $sorts = [
        self::defaultUserGroup => 1
    ];

    public $userGroups = [
        self::specificUserGroup,
        'cHBw'
    ];

    public $images = [
        self::defaultUserGroup => [
            'https://www.store.com/images/pdf304xyz.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/pdf304xyz.png' => Image::TYPE_THUMBNAIL
        ],
        self::specificUserGroup => [
            'https://www.store.com/images/pdf305xyz.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/pdf305xyz.png' => Image::TYPE_THUMBNAIL
        ]
    ];

    public $attributes = [
        'type' => [
            'content',
        ]
    ];

    public $properties = [
        'file_type' => [
            self::defaultUserGroup => 'pdf',
        ],
        'number_of_comments' => [
            self::defaultUserGroup => 9,
        ]
    ];
}