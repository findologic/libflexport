<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Traits\HasAttributes;
use FINDOLOGIC\Export\Traits\HasBonus;
use FINDOLOGIC\Export\Traits\HasDateAdded;
use FINDOLOGIC\Export\Traits\HasDescription;
use FINDOLOGIC\Export\Traits\HasGroups;
use FINDOLOGIC\Export\Traits\HasId;
use FINDOLOGIC\Export\Traits\HasImages;
use FINDOLOGIC\Export\Traits\HasKeywords;
use FINDOLOGIC\Export\Traits\HasName;
use FINDOLOGIC\Export\Traits\HasOrdernumbers;
use FINDOLOGIC\Export\Traits\HasOverriddenPrice;
use FINDOLOGIC\Export\Traits\HasPrice;
use FINDOLOGIC\Export\Traits\HasProperties;
use FINDOLOGIC\Export\Traits\HasSalesFrequency;
use FINDOLOGIC\Export\Traits\HasSort;
use FINDOLOGIC\Export\Traits\HasSummary;
use FINDOLOGIC\Export\Traits\HasUrl;
use FINDOLOGIC\Export\Traits\HasVariants;

abstract class Item implements Serializable
{
    use HasAttributes;
    use HasBonus;
    use HasDateAdded;
    use HasDescription;
    use HasGroups;
    use HasId;
    use HasImages;
    use HasKeywords;
    use HasName;
    use HasOrdernumbers;
    use HasOverriddenPrice;
    use HasPrice;
    use HasProperties;
    use HasSalesFrequency;
    use HasSort;
    use HasSummary;
    use HasUrl;
    use HasVariants;

    public function __construct(string $id)
    {
        $this->setId($id);

        $this->name = new Name();
        $this->summary = new Summary();
        $this->description = new Description();
        $this->price = new Price();
        $this->overriddenPrice = new OverriddenPrice();
        $this->url = new Url();
        $this->bonus = new Bonus();
        $this->salesFrequency = new SalesFrequency();
        $this->dateAdded = new DateAdded();
        $this->sort = new Sort();
        $this->keywords = new AllKeywords();
        $this->ordernumbers = new AllOrdernumbers();
    }
}
