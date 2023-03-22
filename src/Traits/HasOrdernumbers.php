<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\AllOrdernumbers;
use FINDOLOGIC\Export\Data\Ordernumber;

trait HasOrdernumbers
{
    protected AllOrdernumbers $ordernumbers;

    public function getOrdernumbers(): AllOrdernumbers
    {
        return $this->ordernumbers;
    }

    public function addOrdernumber(Ordernumber $ordernumber): void
    {
        $this->checkUsergroupString($ordernumber->getUsergroup());

        $this->ordernumbers->addValue($ordernumber);
    }

    /**
     * @param Ordernumber[] $ordernumbers
     */
    public function setAllOrdernumbers(array $ordernumbers): void
    {
        foreach ($ordernumbers as $ordernumber) {
            $this->checkUsergroupString($ordernumber->getUsergroup());
        }

        $this->ordernumbers->setAllValues($ordernumbers);
    }
}
