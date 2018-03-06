<?php

namespace FINDOLOGIC\Export\Helpers;

class UsergroupAwareUrlValue extends UsergroupAwareSimpleValue
{
    protected function validate($value)
    {
        $value = parent::validate($value);

        $value = DataHelper::validateUrl($value);

        return $value;
    }
}
