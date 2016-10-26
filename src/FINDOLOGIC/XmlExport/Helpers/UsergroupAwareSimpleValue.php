<?php
/**
 * Created by PhpStorm.
 * User: mrc
 * Date: 10/26/16
 * Time: 6:29 PM
 */

namespace FINDOLOGIC\XmlExport\Helpers;


/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\XmlExport\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareSimpleValue extends Serializable
{
    private $collectionName;
    private $itemName;
    private $values = array();

    public function __construct($collectionName, $itemName)
    {
        $this->collectionName = $collectionName;
        $this->itemName = $itemName;
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
        $collectionElem = XmlHelper::createElement($document, $this->collectionName);

        foreach ($this->values as $usergroup => $value) {
            $itemElem = XmlHelper::createElementWithText($document, $this->itemName, $value);
            $collectionElem->appendChild($itemElem);

            if ($usergroup !== '') {
                $itemElem->setAttribute('usergroup', $usergroup);
            }
        }

        return $collectionElem;
    }
}