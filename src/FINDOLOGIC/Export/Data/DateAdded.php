<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class DateAdded extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('dateAddeds', 'dateAdded');
    }

    public function setValue($value, $usergroup = '')
    {
        throw new \BadMethodCallException('Assign DateAdded values by passing a \DateTime to setDateValue()');
    }

    public function setDateValue(\DateTime $value, $usergroup = '')
    {
        $formatted = $value->format(\DateTime::ATOM);

        parent::setValue($formatted, $usergroup);
    }
}