<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;

final class AllKeywords extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allKeywords', 'keywords');
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        if (array_key_exists('', $this->values)) {
            return implode(
                ',',
                array_map(
                    static fn(Keyword $keyword): string => $keyword->getCsvFragment($csvConfig),
                    $this->values['']
                )
            );
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'allKeywords';
    }
}
