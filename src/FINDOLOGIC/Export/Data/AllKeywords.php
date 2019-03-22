<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;

class AllKeywords extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allKeywords', 'keywords', ',');
    }

    /**
     * @param array $availableProperties Properties that are available across the data set, so an individual item
     *      knows into which column to write its property value, if any.
     * @return string A CSV fragment that, combined with other fragments, will finally become an export file.
     */
    public function getCsvFragment(array $availableProperties = []): string
    {
        if (array_key_exists('', $this->values)) {
            return implode(',', array_map(function (Keyword $keyword): string {
                return $keyword->getCsvFragment();
            }, $this->values['']));
        } else {
            return '';
        }
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'allKeywords';
    }
}
