<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Attribute implements Serializable
{
    private $key;

    private $values;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct($key, $values = [])
    {
        $this->key = DataHelper::checkForEmptyValue($key);
        $this->setValues($values);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function addValue($value)
    {
        DataHelper::checkAttributeValueNotExceedingCharacterLimit($this->getKey(), $value);
        array_push($this->values, DataHelper::checkForEmptyValue($value));
    }

    public function setValues($values)
    {
        $this->values = [];

        foreach ($values as $value) {
            $this->addValue($value);
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getKey()
    {
        return $this->key;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $attributeElem = XMLHelper::createElement($document, 'attribute');

        $keyElem = XMLHelper::createElementWithText($document, 'key', $this->getKey());
        $attributeElem->appendChild($keyElem);

        $valuesElem = XMLHelper::createElement($document, 'values');
        $attributeElem->appendChild($valuesElem);

        foreach ($this->getValues() as $value) {
            $valueElem = XMLHelper::createElementWithText($document, 'value', $value);
            $valuesElem->appendChild($valueElem);
        }

        return $attributeElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(array $availableProperties = [])
    {
        $attributeParts = [];

        foreach ($this->getValues() as $value) {
            $attributeParts[] = sprintf('%s=%s', urlencode($this->getKey()), urlencode($value));
        }

        return implode('&', $attributeParts);
    }
}
