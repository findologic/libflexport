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
    private $values = array();

    public function __construct($rootCollectionName, $usergroupCollectionName, $csvDelimiter)
    {
        $this->rootCollectionName = $rootCollectionName;
        $this->usergroupCollectionName = $usergroupCollectionName;
        $this->csvDelimiter = $csvDelimiter;
    }

    public function addValue(UsergroupAwareMultiValueItem $value)
    {
        if (!array_key_exists($value->getUsergroup(), $this->values)) {
            $this->values[$value->getUsergroup()] = array();
        }

        array_push($this->values[$value->getUsergroup()], $value);
    }

    public function setAllValues($values)
    {
        $this->values = $values;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $rootCollectionElem = XMLHelper::createElement($document, $this->rootCollectionName);

        foreach ($this->values as $usergroup => $usergroupValues) {
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

    /**
     * @inheritdoc
     */
    public function getCsvFragment()
    {
        $mergedValues = '';

        if (array_key_exists('', $this->values)) {
            foreach ($this->values[''] as $value) {
                $escapedValue = preg_replace('/' . $this->csvDelimiter . '/', ' ', $value);

                $mergedValues .= $escapedValue . $this->csvDelimiter;
            }
        }

        $trimmedMergedValues = rtrim($mergedValues, $this->csvDelimiter);

        return $trimmedMergedValues;
    }
}
