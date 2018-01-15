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

/* Setup exporter library start */

$exporter = Exporter::create(Exporter::TYPE_XML);

/* Setup exporter library end */

/* Add item to exporter start */

$item = $exporter->createItem('01120c948ad41a2284ad9f0402fbc7d');

/* Add item to exporter end */

/* Adding ordernumbers section start */

/**
 * Provide array with ordernumbers and a usergroup if set;
 */
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
/* Adding ordernumbers section end */

/* Adding names section start */
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
/* Adding names section end */


/* Adding summaries section start */
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
/* Adding summaries section end */


/* Adding descriptions section start */
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
/* Adding descriptions section end */


/* Adding prices section start */
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
/* Adding prices section end */


/* Adding urls section start */
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
/* Adding urls section end */


/* Adding images section start */
$imagesData = [
    '' => [
        'https://www.store.com/images/277KTL.png' => '',
        'https://www.store.com/images/thumbnails/277KTL.png' => Image::TYPE_THUMBNAIL
    ],
    'LNrLF7BRVJ0toQ==' => [
        'https://www.store.com/images/277KTLmen.png' => '',
        'https://www.store.com/images/thumbnails/277KTLmen.png' => Image::TYPE_THUMBNAIL
    ]
];

foreach ($imagesData as $usergroup => $images) {
    foreach ($images as $image => $type) {
        $item->addImage(new Image($image, $type, $usergroup));
    }
}
/* Adding images section end */


/* Adding attributes section start */
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
/* Adding attributes section end */


/* Adding keywords section start */
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
/* Adding keywords section end */


/* Adding usergroups section start */
$usergroups = [
    'LNrLF7BRVJ0toQ==',
    'cHBw'
];

foreach ($usergroups as $usergroup) {
    $item->addUsergroup(new Usergroup($usergroup));
}
/* Adding usergroups section end */


/* Adding bonus section start */
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
/* Adding bonus section end */


/* Adding salesFrequency section start */
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
/* Adding salesFrequency section end */


/* Adding dateAdded section start */
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
/* Adding dateAdded section end */


/* Adding sort section start */
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
/* Adding sort section end */


/* Adding properties section start */
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
/* Adding properties section end */

$xmlExport = $exporter->serializeItems([$item], 0, 1, 1);
