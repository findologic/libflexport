<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Traits\Concerns\Item as ItemConcern;

abstract class Item implements Serializable
{
    use ItemConcern\HandlesAttribute;
    use ItemConcern\HandlesBonus;
    use ItemConcern\HandlesDateAdded;
    use ItemConcern\HandlesDescription;
    use ItemConcern\HandlesId;
    use ItemConcern\HandlesImage;
    use ItemConcern\HandlesKeyword;
    use ItemConcern\HandlesName;
    use ItemConcern\HandlesOrdernumber;
    use ItemConcern\HandlesPrice;
    use ItemConcern\HandlesProperty;
    use ItemConcern\HandlesSalesFrequency;
    use ItemConcern\HandlesSort;
    use ItemConcern\HandlesSummary;
    use ItemConcern\HandlesTaxrate;
    use ItemConcern\HandlesUrl;
    use ItemConcern\HandlesUsergroup;

    public function __construct($id)
    {
        $this->setId($id);

        $this->name = new Name();
        $this->summary = new Summary();
        $this->description = new Description();
        $this->url = new Url();
        $this->bonus = new Bonus();
        $this->salesFrequency = new SalesFrequency();
        $this->dateAdded = new DateAdded();
        $this->sort = new Sort();
        $this->keywords = new AllKeywords();
        $this->ordernumbers = new AllOrdernumbers();
    }

    /**
     * @inheritdoc
     */
    abstract public function getDomSubtree(DOMDocument $document);
}
