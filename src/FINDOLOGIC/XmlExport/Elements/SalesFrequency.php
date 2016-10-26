<?php

namespace FINDOLOGIC\XmlExport\Elements;


use FINDOLOGIC\XmlExport\Helpers\UsergroupAwareSimpleValue;

class SalesFrequency extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }
}