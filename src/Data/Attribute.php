<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Attribute implements Serializable, NameAwareValue
{
    /** @var string */
    private $key;

    /** @var array */
    private $values;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @var string $key The name of the attribute.
     * @var array $values The attribute values to set.
     */
    public function __construct(string $key, array $values = [])
    {
        $this->key = DataHelper::checkForEmptyValue($this->getValueName(), $key);
        $this->setValues($values);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param mixed $value
     */
    public function addValue($value): void
    {
        DataHelper::checkAttributeValueNotExceedingCharacterLimit($this->getKey(), $value);
        array_push($this->values, DataHelper::checkForEmptyValue($this->getValueName(), $value));
    }

    public function setValues(array $values): void
    {
        $this->values = [];

        foreach ($values as $value) {
            $this->addValue($value);
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
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
     * @param int $imageCount
     * @inheritdoc
     */
    public function getCsvFragment(
        array $availableProperties = [],
        array $availableAttributes = [],
        int $imageCount = 1
    ): string {
        $attributeParts = [];

        foreach ($this->getValues() as $value) {
            DataHelper::checkCsvAttributeKeyNotExceedingCharacterLimit($this->getKey());
            $attributeParts[] = sprintf('%s=%s', urlencode($this->getKey()), urlencode($value));
        }

        return implode('&', $attributeParts);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'attribute';
    }
}
