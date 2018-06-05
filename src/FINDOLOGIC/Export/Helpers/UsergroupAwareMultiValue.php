<?php

namespace FINDOLOGIC\Export\Helpers;

/**
 * Class UsergroupAwareMultiValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Multi values that can differ per usergroup, and have multiple values for each.
 */
abstract class UsergroupAwareMultiValue implements Serializable
{
    private $rootCollectionName;
    private $usergroupCollectionName;
    private $csvDelimiter;
    protected $values = [];

    public function __construct($rootCollectionName, $usergroupCollectionName, $csvDelimiter)
    {
        $this->rootCollectionName = $rootCollectionName;
        $this->usergroupCollectionName = $usergroupCollectionName;
        $this->csvDelimiter = $csvDelimiter;
    }

    /**
     * @param UsergroupAwareMultiValueItem $value The element to add the the collection.
     */
    public function addValue(UsergroupAwareMultiValueItem $value)
    {
        if (!array_key_exists($value->getUsergroup(), $this->getValues())) {
            $this->values[$value->getUsergroup()] = [];
        }

        array_push($this->values[$value->getUsergroup()], $value);
    }

    /**
     * @param array $values Array of elements to be added to the collection.
     */
    public function setAllValues($values)
    {
        $this->values = [];

        /* @var UsergroupAwareMultiValueItem $value */
        foreach ($values as $value) {
            $this->addValue($value);
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $rootCollectionElem = XMLHelper::createElement($document, $this->rootCollectionName);

        foreach ($this->getValues() as $usergroup => $usergroupValues) {
            $usergroupCollectionElem = XMLHelper::createElement($document, $this->usergroupCollectionName);
            if ($usergroup) {
                $usergroupCollectionElem->setAttribute('usergroup', $usergroup);
            }
            $rootCollectionElem->appendChild($usergroupCollectionElem);

            /** @var UsergroupAwareMultiValueItem $value */
            foreach ($usergroupValues as $value) {
                $usergroupCollectionElem->appendChild($value->getDomSubtree($document));
            }
        }

        return $rootCollectionElem;
    }
}
