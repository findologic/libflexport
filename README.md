# FINDOLOGIC export toolkit

[![Travis](https://img.shields.io/travis/findologic/libflexport.svg)](https://travis-ci.org/findologic/libflexport)
[![Latest Stable Version](https://poser.pugx.org/findologic/libflexport/v/stable)](https://packagist.org/packages/findologic/libflexport)

## Synopsis

This project provides a export library for XML and CSV generation according to the FINDOLOGIC export patterns.
* XML: <https://docs.findologic.com/doku.php?id=export_patterns:xml>
* CSV: <https://docs.findologic.com/doku.php?id=export_patterns:csv>



```PHP
## Basic usage

require_once './vendor/autoload.php';

use \FINDOLOGIC\Export\XML\Exporter;
use \FINDOLOGIC\Export\Data\Price;

$exporter = Exporter::create(Exporter::TYPE_XML);

$item = $exporter->createItem('123');

$price = new Price();
$price->setValue('13.37');
$item->setPrice($price);

$exporter->serializeItems(array($item), 0, 1);
```

## Setup

1. Include as composer dependency using `composer require findologic/libflexport`
2. Run `composer install` to generate the vendor class autoloader
3. Load `./vendor/autoload.php` into the project

## Contributors

If you want to contribute to this project, feel free to fork the repository. Afterwards you can create a pull request following the steps mentioned at this Github help page: 
* <https://help.github.com/articles/creating-a-pull-request-from-a-fork/>

Tests should be provided if possible.

License
=======

The contents of this repository are licensed according to the LICENSE file.