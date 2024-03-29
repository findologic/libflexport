<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Traits\HasAttributes;
use FINDOLOGIC\Export\Traits\HasGroups;
use FINDOLOGIC\Export\Traits\HasId;
use FINDOLOGIC\Export\Traits\HasImages;
use FINDOLOGIC\Export\Traits\HasName;
use FINDOLOGIC\Export\Traits\HasOrdernumbers;
use FINDOLOGIC\Export\Traits\HasOverriddenPrice;
use FINDOLOGIC\Export\Traits\HasParentId;
use FINDOLOGIC\Export\Traits\HasPrice;
use FINDOLOGIC\Export\Traits\HasProperties;
use FINDOLOGIC\Export\Traits\HasUrl;

abstract class Variant implements Serializable
{
    use HasAttributes;
    use HasGroups;
    use HasId;
    use HasImages;
    use HasName;
    use HasOrdernumbers;
    use HasOverriddenPrice;
    use HasParentId;
    use HasPrice;
    use HasProperties;
    use HasUrl;

    public function __construct(string $id, string $parentId)
    {
        $this->setId($id);
        $this->setParentId($parentId);

        $this->name = new Name();
        $this->ordernumbers = new AllOrdernumbers();
        $this->price = new Price();
        $this->overriddenPrice = new OverriddenPrice();
        $this->url = new Url();
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
