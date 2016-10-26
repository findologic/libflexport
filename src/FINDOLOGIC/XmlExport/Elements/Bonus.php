<?php

namespace FINDOLOGIC\XmlExport\Elements;


use FINDOLOGIC\XmlExport\Helpers\UsergroupAwareSimpleValue;

class Bonus extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }
}