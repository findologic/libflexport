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
use Stringable;

final class Group implements Serializable, NameAwareValue, Stringable
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->value = DataHelper::checkForEmptyValue($this->getValueName(), $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        return XMLHelper::createElementWithText($document, 'group', $this->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'group';
    }
}
