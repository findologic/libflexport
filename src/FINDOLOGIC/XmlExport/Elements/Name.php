<?php

namespace FINDOLOGIC\XmlExport\Elements;


use FINDOLOGIC\XmlExport\Helpers\UsergroupAwareSimpleValue;

class Name extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('names', 'name');
    }
}