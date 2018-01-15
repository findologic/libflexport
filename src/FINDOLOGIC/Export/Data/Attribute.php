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
        array_push($this->values, DataHelper::checkForEmptyValue($value));
    }

    public function setValues($values)
    {
        $this->values = [];

        foreach ($values as $value) {
            $this->addValue($value);
        }
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

        $keyElem = XMLHelper::createElementWithText($document, 'key', $this->key);
        $attributeElem->appendChild($keyElem);

        $valuesElem = XMLHelper::createElement($document, 'values');
        $attributeElem->appendChild($valuesElem);

        foreach ($this->values as $value) {
            $valueElem = XMLHelper::createElementWithText($document, 'value', $value);
            $valuesElem->appendChild($valueElem);
        }

        return $attributeElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment()
    {
        $encoded = '';

        foreach ($this->values as $value) {
            $encoded .= sprintf('%s=%s&', urlencode($this->key), urlencode($value));
        }

        $encoded = rtrim($encoded, '&');

        return $encoded;
    }
}
