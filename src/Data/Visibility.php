<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareBoolValue;

class Visibility extends UsergroupAwareBoolValue
{
    public function __construct()
    {
        parent::__construct('visibilities', 'visible', true);
    }

    public function getValueName(): string
    {
        return 'visible';
    }
}
