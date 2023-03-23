<?php

namespace FINDOLOGIC\Export\Helpers;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;

/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareSimpleValue implements Serializable, NameAwareValue
{
    private string $collectionName;

    private string $itemName;

    /** @var array */
    protected $values = [];

    public function __construct(string $collectionName, string $itemName)
    {
        $this->collectionName = $collectionName;
        $this->itemName = $itemName;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $value The value of the element.
     * @param string $usergroup The usergroup of the element.
     */
    public function setValue(mixed $value, string $usergroup = ''): void
    {
        $this->values[$usergroup] = $this->validate($value);
    }

    public function hasUsergroup(): bool
    {
        return count(
            array_filter(
                array_keys($this->values),
                static fn(string $userGroup) => $userGroup !== ''
            )
        ) > 0;
    }

    /**
     * Validates given value.
     * Basic implementation is validating against an empty string,
     * but is overridden when checking values more specific.
     *
     * When valid returns given value.
     * When not valid an exception is thrown.
     *
     * @param mixed $value Validated value.
     * @return mixed
     * @throws EmptyValueNotAllowedException
     */
    protected function validate(mixed $value): mixed
    {
        $value = trim($value);

        if ($value === '') {
            throw new EmptyValueNotAllowedException($this->getValueName());
        }

        return $value;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        $collectionElem = XMLHelper::createElement($document, $this->collectionName);

        foreach ($this->getValues() as $usergroup => $value) {
            $itemElem = XMLHelper::createElementWithText($document, $this->itemName, $value);
            $collectionElem->appendChild($itemElem);

            if ($usergroup !== '') {
                $itemElem->setAttribute('usergroup', $usergroup);
            }
        }

        return $collectionElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $value = '';

        if (array_key_exists('', $this->getValues())) {
            $value = $this->getValues()[''];
        }

        return $value;
    }
}
