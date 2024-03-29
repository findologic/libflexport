<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareBoolValue;

final class Visibility extends UsergroupAwareBoolValue
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
