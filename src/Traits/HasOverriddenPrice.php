<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\OverriddenPrice;
use InvalidArgumentException;

trait HasOverriddenPrice
{
    protected OverriddenPrice $overriddenPrice;

    public function getOverriddenPrice(): OverriddenPrice
    {
        return $this->overriddenPrice;
    }

    public function setOverriddenPrice(OverriddenPrice $overriddenPrice): void
    {
        $this->checkUsergroupAwareValue($overriddenPrice);

        $this->overriddenPrice = $overriddenPrice;
    }

    public function addOverriddenPrice(string|int|float $overriddenPrice, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->overriddenPrice->setValue($overriddenPrice, $usergroup);
    }

    /**
     * @param OverriddenPrice[] $overriddenPrices
     */
    public function setAllOverriddenPrices(array $overriddenPrices): void
    {
        foreach ($overriddenPrices as $overriddenPrice) {
            if (!$overriddenPrice instanceof OverriddenPrice) {
                throw new InvalidArgumentException(sprintf(
                    'Given overridden prices must be instances of %s',
                    OverriddenPrice::class
                ));
            }

            $this->checkUsergroupAwareValue($overriddenPrice);

            foreach ($overriddenPrice->getValues() as $usergroup => $value) {
                $this->addOverriddenPrice($value, $usergroup);
            }
        }
    }
}
