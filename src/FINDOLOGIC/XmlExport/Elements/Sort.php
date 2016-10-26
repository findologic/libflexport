<?php

namespace FINDOLOGIC\XmlExport\Elements;


use FINDOLOGIC\XmlExport\Helpers\UsergroupAwareSimpleValue;

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }
}