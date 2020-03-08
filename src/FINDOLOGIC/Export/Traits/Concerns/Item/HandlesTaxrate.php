<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

trait HandlesTaxrate
{
    /** @var float */
    protected $taxRate;

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set the tax rate of the item. This is only relevant for CSV export type.
     *
     * @param float $taxRate The tax rate of the item.
     */
    public function setTaxRate(float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }
}
