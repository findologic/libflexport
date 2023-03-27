<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use FINDOLOGIC\Export\Examples\Data\ExampleContentItemWithoutUsergroups;
use FINDOLOGIC\Export\Examples\Data\ExampleProductItemWithVariants;

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
