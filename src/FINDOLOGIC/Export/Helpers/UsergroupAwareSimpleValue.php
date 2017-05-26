<?php

namespace FINDOLOGIC\Export\Helpers;


/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\XML\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareSimpleValue implements Serializable
{
    private $collectionName;
    private $itemName;
    private $values = array();

    public function __construct($collectionName, $itemName)
    {
        $this->collectionName = $collectionName;
        $this->itemName = $itemName;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValue($value, $usergroup = '')
    {
        $this->values[$usergroup] = $value;
    }

    /**
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