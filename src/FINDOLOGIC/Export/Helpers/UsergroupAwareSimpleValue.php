<?php

namespace FINDOLOGIC\Export\Helpers;

/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareSimpleValue implements Serializable
{
    private $collectionName;
    private $itemName;
    private $values = [];

    public function __construct($collectionName, $itemName)
    {
        $this->collectionName = $collectionName;
        $this->itemName = $itemName;
    }

    public function getValues()
    {
        return $this->values;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function setValue($value, $usergroup = '')
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
     * @param $value string|int Validated value.
     * @return string string|int
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
    public function getDomSubtree(\DOMDocument $document)
    {
        $collectionElem = XMLHelper::createElement($document, $this->collectionName);

        foreach ($this->values as $usergroup => $value) {
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
    public function getCsvFragment()
    {
        if (array_key_exists('', $this->values)) {
            $value = $this->values[''];
        } else {
            $value = '';
        }

        return $value;
    }
}
