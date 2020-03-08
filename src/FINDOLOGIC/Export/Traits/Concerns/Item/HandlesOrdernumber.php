<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\AllOrdernumbers;
use FINDOLOGIC\Export\Data\Ordernumber;

trait HandlesOrdernumber
{
    /** @var AllOrdernumbers */
    protected $ordernumbers;

    /**
     * @param Ordernumber $ordernumber The ordernumber element to add to the item.
     */
    public function addOrdernumber(Ordernumber $ordernumber): void
    {
        $this->ordernumbers->addValue($ordernumber);
    }

    /**
     * @param array $ordernumbers Array of ordernumber elements which should be added to the item.
     */
    public function setAllOrdernumbers(array $ordernumbers): void
    {
        $this->ordernumbers->setAllValues($ordernumbers);
    }
}
