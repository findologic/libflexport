<?php

namespace FINDOLOGIC\Export\Data;

use BadMethodCallException;
use DateTime;
use DateTimeInterface;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class DateAdded extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('dateAddeds', 'dateAdded');
    }

    public function setValue($value, string $usergroup = ''): void
    {
        throw new BadMethodCallException(
            sprintf('Assign DateAdded values by passing a %s to setDateValue()', DateTimeInterface::class)
        );
    }

    public function setDateValue(DateTimeInterface $value, string $usergroup = ''): void
    {
        $formatted = $value->format(DateTime::ATOM);

        parent::setValue($formatted, $usergroup);
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $date = DateTime::createFromFormat(DATE_ATOM, $this->getValues()['']);

        return $date->format(DATE_ATOM);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'dateAdded';
    }
}
