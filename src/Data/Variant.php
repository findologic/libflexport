<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Traits\HasAttributes;
use FINDOLOGIC\Export\Traits\HasGroups;
use FINDOLOGIC\Export\Traits\HasId;
use FINDOLOGIC\Export\Traits\HasName;
use FINDOLOGIC\Export\Traits\HasOrdernumbers;
use FINDOLOGIC\Export\Traits\HasOverriddenPrice;
use FINDOLOGIC\Export\Traits\HasParentId;
use FINDOLOGIC\Export\Traits\HasPrice;
use FINDOLOGIC\Export\Traits\HasProperties;

abstract class Variant implements Serializable
{
    use HasAttributes;
    use HasGroups;
    use HasId;
    use HasName;
    use HasOrdernumbers;
    use HasOverriddenPrice;
    use HasParentId;
    use HasPrice;
    use HasProperties;

    public function __construct($id, $parentId)
    {
        $this->setId($id);
        $this->setParentId($parentId);

        $this->name = new Name();
        $this->ordernumbers = new AllOrdernumbers();
        $this->price = new Price();
        $this->overriddenPrice = new OverriddenPrice();
    }

    public function checkUsergroupString(string $usergroup): void
    {
        // Not needed for variants
    }

    public function checkUsergroupAwareValue(UsergroupAwareSimpleValue|UsergroupAwareMultiValue $value): void
    {
        // Not needed for variants
    }
}
