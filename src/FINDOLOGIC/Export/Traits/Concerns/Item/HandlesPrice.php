<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Price;
use InvalidArgumentException;

trait HandlesPrice
{
    /** @var Price */
    protected $price;

    /** @var float */
    protected $insteadPrice;

    /** @var float */
    protected $maxPrice;

    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price The price element to add to the item.
     */
    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    /**
     * Shortcut to easily add the price of the item.
     *
     * @param string $price The price of the item.
     * @param string $usergroup The usergroup of the price.
     */
    public function addPrice($price, $usergroup = ''): void
    {
        if ($this->price === null) {
            $this->price = new Price();
        }

        $this->price->setValue($price, $usergroup);
    }

    /**
     * @param Price[] $prices
     */
    public function setAllPrices(array $prices): void
    {
        foreach ($prices as $price) {
            if (!$price instanceof Price) {
                throw new InvalidArgumentException(sprintf(
                    'Given prices must be instances of %s',
                    Price::class
                ));
            }

            foreach ($price->getValues() as $usergroup => $value) {
                $this->addPrice($value, $usergroup);
            }
        }
    }

    public function getInsteadPrice()
    {
        return $this->insteadPrice;
    }

    /**
     * Set the instead price of the item. This is only relevant for CSV export type.
     *
     * @param float $insteadPrice The instead price of the item.
     */
    public function setInsteadPrice(float $insteadPrice): void
    {
        $this->insteadPrice = $insteadPrice;
    }

    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set the max price of the item. This is only relevant for CSV export type.
     *
     * @param float $maxPrice The instead price of the item.
     */
    public function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }
}
