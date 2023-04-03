<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Price;
use InvalidArgumentException;

trait HasPrice
{
    protected Price $price;

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->checkUsergroupAwareValue($price);

        $this->price = $price;
    }

    public function addPrice(string|int|float $price, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

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

            $this->checkUsergroupAwareValue($price);

            foreach ($price->getValues() as $usergroup => $value) {
                $this->addPrice($value, $usergroup);
            }
        }
    }
}
