# FINDOLOGIC export toolkit

[![Travis](https://img.shields.io/travis/findologic/libflexport.svg)](https://travis-ci.org/findologic/libflexport)
[![Latest Stable Version](https://poser.pugx.org/findologic/libflexport/v/stable)](https://packagist.org/packages/findologic/libflexport)
[![Code Climate](https://codeclimate.com/github/findologic/libflexport.svg)](https://codeclimate.com/github/findologic/libflexport)
[![Codecov](https://codecov.io/gh/findologic/libflexport/branch/develop/graph/badge.svg)](https://codecov.io/gh/findologic/libflexport)

## Table of Contents

1. [Synopsis](#synopsis)
2. [Export recommendation](#export-recommendation)
3. [Limitations](#limitations)
4. [Basic usage](#basic-usage)
    1. [Setup](#setup)
    2. [XML Export](#xml-export)
    3. [CSV Export](#csv-export)
5. [Examples](#examples)
6. [Contributors](#contributors)

## Synopsis

This project provides an export library for XML and CSV generation according to the FINDOLOGIC export patterns.
* XML <https://docs.findologic.com/doku.php?id=export_patterns:xml>
* CSV <https://docs.findologic.com/doku.php?id=export_patterns:csv>
  * Note that CSV support is still relatively new. Consider it beta-quality.

#### Export recommendation

Using the XML export is recommended by FINDOLOGIC. The XML is easier to read and has some advantages over the CSV export like:

* No encoding issues as the encoding attribute is provided in the XML response `<?xml version="1.0" encoding="UTF-8"?>`.
* Validation is more reliable.
* Simple escaping of content using the `<![CDATA[...]]>`-tag.
* Standardized structure.
* Dynamically extract the products from the database via `start` and `count` parameter in the url.
* No limited file size for XML because of pagination.
* Using multiple usergroups per product.

The key advantage for CSV is that it is possible to use way more groups than XML usergroups. On the other hand:

* Groups only regulate visibility - it's not possible to show different values per group.
* The format is prone to encoding issues if non-UTF-8 data is fed into it.
* Total export size is limited by file size, while XML pagination theoretically allows exports of arbitrary size. 

#### Limitations

Currently, only input text encoded in UTF-8 is supported. To use this library with other types of encoding, one of the
following is necessary:

* Convert all text to UTF-8 prior to passing it to `libflexport`.
* Use the XML exporter and modify the library to change the XML header to contain the required encoding.
  * FINDOLOGIC is capable of handling most encodings, but only with XML.

## Basic usage

### Setup

1. Include as composer dependency using `composer require findologic/libflexport`
2. Load `./vendor/autoload.php` into the project

### XML export

```php
require_once './vendor/autoload.php';

use \FINDOLOGIC\Export\Exporter;

$exporter = Exporter::create(Exporter::TYPE_XML);

$item = $exporter->createItem('123');

$item->addPrice(13.37);
// Obsolete long form:
// $price = new Price();
// $price->setValue(13.37);
// $item->setPrice($price);

$xmlOutput = $exporter->serializeItems([$item], 0, 1, 1);
```

You may want to include the header `header('Content-type: text/xml')`.

### CSV export

```php
require_once './vendor/autoload.php';

use \FINDOLOGIC\Export\Exporter;

$exporter = Exporter::create(Exporter::TYPE_CSV);

$item = $exporter->createItem('123');

$item->addPrice(13.37);
// Obsolete long form:
// $price = new Price();
// $price->setValue(13.37);
// $item->setPrice($price);

// Date is mandatory for CSV.
$item->addDateAdded(new \DateTime());

$csvOutput = $exporter->serializeItems([$item], 0, 1, 1);
```

### Examples

For more specific examples, please have a look at the examples directory.

## Contributors

If you want to contribute to this project, feel free to fork the repository. Afterwards you can create a pull request following the steps mentioned at [this Github help page.](https://help.github.com/articles/creating-a-pull-request-from-a-fork/)

Tests should be provided if possible.

Running `php-cs-fixer` before committing will reduce style-caused build failures.
