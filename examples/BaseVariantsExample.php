<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BaseExample.php';

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exporter;

abstract class BaseVariantsExample extends BaseExample
{
    public function __construct()
    {
        parent::__construct();

        $this->products = [];
        $this->products[] = new ExampleProductItemWithVariants();
        $this->products[] = new ExampleContentItemWithoutUsergroups();
    }
}
