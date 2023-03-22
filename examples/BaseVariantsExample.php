<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BaseExample.php';

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
