<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;

class AllOrdernumbers extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allOrdernumbers', 'ordernumbers', '|');
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        if (array_key_exists('', $this->values)) {
            return implode('|', array_map(function (Ordernumber $ordernumber) use ($csvConfig): string {
                return $ordernumber->getCsvFragment($csvConfig);
            }, $this->values['']));
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'allOrdernumbers';
    }
}
