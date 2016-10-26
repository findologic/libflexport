<?php

namespace FINDOLOGIC\XmlExport\Elements;


use FINDOLOGIC\XmlExport\Helpers\Serializable;
use FINDOLOGIC\XmlExport\Helpers\XmlHelper;

class Item extends Serializable
{
    private $id;

    /** @var Name */
    private $name;

    /** @var Summary */
    private $summary;

    /** @var Description */
    private $description;

    /** @var Price */
    private $price;

    /** @var Url */
    private $url;

    private $bonus;

    private $salesFrequency;

    private $dateAdded;

    private $sort;

    private $properties = array();

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function setName(Name $name)
    {
        $this->name = $name;
    }

    public function setSummary(Summary $summary)
    {
        $this->summary = $summary;
    }

    public function setDescription(Description $description)
    {
        $this->description = $description;
    }

    public function setPrice(Price $price)
    {
        $this->price = $price;
    }

    public function setUrl(Url $url)
    {
        $this->url = $url;
    }

    public function setBonus(Bonus $bonus)
    {
        $this->bonus = $bonus;
    }

    public function setSalesFrequency(SalesFrequency $salesFrequency)
    {
        $this->salesFrequency = $salesFrequency;
    }

    public function setDateAdded(DateAdded $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    public function setSort(Sort $sort)
    {
        $this->sort = $sort;
    }

    public function addProperty(Property $property)
    {
        foreach ($property->getAllValues() as $usergroup => $value) {
            if (!array_key_exists($usergroup, $this->properties)) {
                $this->properties[$usergroup] = array();
            }
            // No need to check if there are duplicate values for a single property and usergroup, because
            // Property::addValue() already takes care of that.

            $this->properties[$usergroup][$property->getKey()] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $itemElem = XmlHelper::createElement($document, 'item', array('id' => $this->id));
        $document->appendChild($itemElem);

        $itemElem->appendChild($this->name->getDomSubtree($document));
        $itemElem->appendChild($this->summary->getDomSubtree($document));
        $itemElem->appendChild($this->description->getDomSubtree($document));
        $itemElem->appendChild($this->price->getDomSubtree($document));
        $itemElem->appendChild($this->url->getDomSubtree($document));
        $itemElem->appendChild($this->bonus->getDomSubtree($document));
        $itemElem->appendChild($this->salesFrequency->getDomSubtree($document));
        $itemElem->appendChild($this->dateAdded->getDomSubtree($document));
        $itemElem->appendChild($this->sort->getDomSubtree($document));

        // TODO: lots of stuff

        $itemElem->appendChild($this->buildProperties($document));
        $itemElem->appendChild($this->buildAttributes($document));
        $itemElem->appendChild($this->buildOrdernumbers($document));
        $itemElem->appendChild($this->buildImages($document));
        $itemElem->appendChild($this->buildKeywords($document));
        $itemElem->appendChild($this->buildUsergroups($document));

        return $itemElem;
    }

    private function buildProperties(\DOMDocument $document)
    {
        $allProps = XmlHelper::createElement($document, 'allProperties');

        foreach ($this->properties as $usergroup => $usergroupSpecificProperties) {
            $usergroupPropsElem = XmlHelper::createElement($document, 'properties');
            if ($usergroup !== null) {
                $usergroupPropsElem->setAttribute('usergroup', $usergroup);
            }
            $allProps->appendChild($usergroupPropsElem);

            foreach ($usergroupSpecificProperties as $key => $value) {
                $propertyElem = XmlHelper::createElement($document, $key);
                $usergroupPropsElem->appendChild($propertyElem);

                $keyElem = XmlHelper::createElementWithText($document, 'key', $key);
                $propertyElem->appendChild($keyElem);

                $valueElem = XmlHelper::createElementWithText($document, 'value', $value);
                $propertyElem->appendChild($valueElem);
            }
        }

        return $allProps;
    }

    private function buildAttributes(\DOMDocument $document)
    {
        $allAttributes = XmlHelper::createElement($document, 'allAttributes');

        // TODO

        return $allAttributes;
    }

    private function buildOrdernumbers(\DOMDocument $document)
    {
        $allOrdernumbers = XmlHelper::createElement($document, 'allOrdernumbers');

        // TODO

        return $allOrdernumbers;
    }

    private function buildImages(\DOMDocument $document)
    {
        $allImages = XmlHelper::createElement($document, 'allImages');

        // TODO

        return $allImages;
    }

    private function buildKeywords(\DOMDocument $document)
    {
        $allKeywords = XmlHelper::createElement($document, 'allKeywords');

        // TODO

        return $allKeywords;
    }

    private function buildUsergroups(\DOMDocument $document)
    {
        $usergroups = XmlHelper::createElement($document, 'usergroups');

        // TODO

        return $usergroups;
    }
}