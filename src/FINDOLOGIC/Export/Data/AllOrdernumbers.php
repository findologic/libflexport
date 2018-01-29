<?php

namespace FINDOLOGIC\Export\Data;

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
    public function getCsvFragment(array $availableProperties = [])
    {
        if (array_key_exists('', $this->values)) {
            return implode('|', array_map(function ($ordernumber) { return $ordernumber->getValue(); }, $this->values['']));
        } else {
            return '';
        }
    }
}
