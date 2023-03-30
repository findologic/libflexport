<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

final class Attribute implements Serializable, NameAwareValue
{
    private readonly string $key;

    private array $values;

    /**
     * @var string $key The name of the attribute.
     * @var array $values The attribute values to set.
     */
    public function __construct(string $key, array $values = [])
    {
        $this->key = DataHelper::checkForEmptyValue($this->getValueName(), $key);
        $this->setValues($values);
    }

    public function addValue(mixed $value): void
    {
        DataHelper::checkAttributeValueNotExceedingCharacterLimit($this->getKey(), (string) $value);
        $this->values[] = DataHelper::checkForEmptyValue($this->getValueName(), $value);
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
            $valueElem = XMLHelper::createElementWithText($document, 'value', (string) $value);
            $valuesElem->appendChild($valueElem);
        }

        return $attributeElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $sanitizedValues = array_map(
            static function (string $value): string {
                $sanitized = DataHelper::sanitize($value);
                return addcslashes($sanitized, ',');
            },
            $this->getValues()
        );

        return implode(',', $sanitizedValues);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'attribute';
    }
}
