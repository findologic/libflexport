<?php

namespace FINDOLOGIC\Export\Helpers;

/**
 * Class UsergroupAwareMultiValueItem
 * @package FINDOLOGIC\Export\Helpers
 *
 * Single value for a UsergroupAwareMultiValue.
 *
 * When inheriting, make sure that the child class' constructor exposes $value and $usergroup, and calls the parent's
 * constructor with those values, plus the name of the XML element in which the value is wrapped.
 */
abstract class UsergroupAwareMultiValueItem implements Serializable
{
    private $itemName;
    private $value;
    private $usergroup;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct($itemName, $value, $usergroup)
    {
        $this->value = DataHelper::checkForEmptyValue($value);
        $this->itemName = $itemName;
        $this->usergroup = $usergroup;
    }

    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $valueElem = XMLHelper::createElementWithText($document, $this->itemName, $this->value);

        return $valueElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment()
    {
        return $this->value;
    }
}
