<?php

namespace FINDOLOGIC\Export\Data;

use BadMethodCallException;
use DateTime;
use DateTimeInterface;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class DateAdded extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('dateAddeds', 'dateAdded');
    }

    public function setValue($value, string $usergroup = ''): void
    {
        throw new BadMethodCallException('Assign DateAdded values by passing a \DateTime to setDateValue()');
    }

    public function setDateValue(DateTimeInterface $value, string $usergroup = ''): void
    {
        $formatted = $value->format(DateTime::ATOM);

        parent::setValue($formatted, $usergroup);
    }

    public function getCsvFragment(array $availableProperties = []): string
    {
        $date = DateTime::createFromFormat(DATE_ATOM, $this->getValues()['']);

        return $date->format('U');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'dateAdded';
    }
}
