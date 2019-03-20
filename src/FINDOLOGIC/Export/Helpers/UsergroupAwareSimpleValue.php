<?php

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;

/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareSimpleValue implements Serializable
{
    /** @var string */
    private $collectionName;

    /** @var string */
    private $itemName;

    /** @var array */
    protected $values = [];

    public function __construct($collectionName, $itemName)
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
     * @param string|int|float $value The value of the element.
     * @param string $usergroup The usergroup of the element.
     */
    public function setValue($value, string $usergroup = ''): void
    {
        $this->values[$usergroup] = $this->validate($value);
    }

    /**
     * Validates given value.
     * Basic implementation is validating against an empty string,
     * but is overridden when checking values more specific.
     *
     * When valid returns given value.
     * When not valid an exception is thrown.
     *
     * @param string|int $value Validated value.
     * @return string
     * @throws EmptyValueNotAllowedException
     */
    protected function validate($value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new EmptyValueNotAllowedException();
        }

        return $value;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document): \DOMElement
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
    public function getCsvFragment(array $availableProperties = []): string
    {
        $value = '';

        if (array_key_exists('', $this->getValues())) {
            $value = $this->getValues()[''];
        }

        return $value;
    }
}
