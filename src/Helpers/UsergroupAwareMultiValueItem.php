<?php

namespace FINDOLOGIC\Export\Helpers;

use DOMDocument;
use DOMElement;

/**
 * Class UsergroupAwareMultiValueItem
 * @package FINDOLOGIC\Export\Helpers
 *
 * Single value for a UsergroupAwareMultiValue.
 *
 * When inheriting, make sure that the child class' constructor exposes $value and $usergroup, and calls the parent's
 * constructor with those values, plus the name of the XML element in which the value is wrapped.
 */
abstract class UsergroupAwareMultiValueItem implements Serializable, NameAwareValue
{
    /** @var string */
    private $itemName;

    /** @var string */
    private $value;

    /** @var string */
    private $usergroup;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $itemName
     * @param mixed $value
     * @param string|null $usergroup
     */
    public function __construct($itemName, $value, $usergroup)
    {
        $this->value = DataHelper::checkForEmptyValue($this->getValueName(), $value);
        $this->itemName = $itemName;
        $this->usergroup = $usergroup;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        $valueElem = XMLHelper::createElementWithText($document, $this->itemName, $this->getValue());

        return $valueElem;
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
        return $this->getValue();
    }
}
