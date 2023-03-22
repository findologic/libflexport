<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\SalesFrequency;

trait HasSalesFrequency
{
    use SupportsUserGroups;

    protected SalesFrequency $salesFrequency;

    public function getSalesFrequency(): SalesFrequency
    {
        return $this->salesFrequency;
    }

    public function setSalesFrequency(SalesFrequency $salesFrequency): void
    {
        $this->checkUsergroupAwareValue($salesFrequency);

        $this->salesFrequency = $salesFrequency;
    }

    public function addSalesFrequency(int $salesFrequency, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->salesFrequency->setValue($salesFrequency, $usergroup);
    }
}
