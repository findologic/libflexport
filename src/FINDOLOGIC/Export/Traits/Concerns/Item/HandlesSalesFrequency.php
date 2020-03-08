<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\SalesFrequency;

trait HandlesSalesFrequency
{
    /** @var SalesFrequency */
    protected $salesFrequency;

    public function getSalesFrequency(): SalesFrequency
    {
        return $this->salesFrequency;
    }

    /**
     * @param SalesFrequency $salesFrequency The sales frequency element to add to the item.
     */
    public function setSalesFrequency(SalesFrequency $salesFrequency): void
    {
        $this->salesFrequency = $salesFrequency;
    }

    /**
     * Shortcut to easily add the sales frequency of the item. The value must be a positive integer.
     *
     * @param int $salesFrequency The sales frequency of the item.
     * @param string $usergroup The usergroup of the sales frequency.
     */
    public function addSalesFrequency(int $salesFrequency, string $usergroup = ''): void
    {
        $this->salesFrequency->setValue($salesFrequency, $usergroup);
    }
}
