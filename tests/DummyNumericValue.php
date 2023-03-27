<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

final class DummyNumericValue extends UsergroupAwareNumericValue
{
    /**
     * @return string The name of the value, e.g. name or description, which is used for more accurate error reporting.
     */
    public function getValueName(): string
    {
        return 'dummy';
    }
}
