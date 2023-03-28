<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

trait SupportsUserGroups
{
    abstract public function checkUsergroupString(string $usergroup): void;

    abstract public function checkUsergroupAwareValue(UsergroupAwareSimpleValue|UsergroupAwareMultiValue $value): void;
}
