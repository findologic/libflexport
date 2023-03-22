<?php

use FINDOLOGIC\Export\Data\Image;

/*
 * This class represents an example content.
 * As this is just a static class, an own logic must be implemented
 */

class ExampleContentItemWithoutUsergroups extends ExampleContentItem
{
    public string $id = 'content_f1daaa5dbb744b4f9965f151b3eaf069';

    public array $orderNumbers = [
        self::DEFAULT_USER_GROUP => [
            'pdf304xyz',
            '9424585418519'
        ],
    ];

    public array $urls = [
        self::DEFAULT_USER_GROUP => 'https://www.store.com/documents/pdf304xyz.pdf',
    ];

    public array $keywords = [
        self::DEFAULT_USER_GROUP => [
            'pdf304xyz',
            '9424585418519'
        ]
    ];

    public array $images = [
        self::DEFAULT_USER_GROUP => [
            'https://www.store.com/images/pdf304xyz.png' => Image::TYPE_DEFAULT,
            'https://www.store.com/images/thumbnails/pdf304xyz.png' => Image::TYPE_THUMBNAIL
        ],
    ];
}
