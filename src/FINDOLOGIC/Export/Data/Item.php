<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\Serializable;

abstract class Item implements Serializable
{
    protected $id;

    /** @var Name */
    protected $name;

    /** @var Summary */
    protected $summary;

    /** @var Description */
    protected $description;

    /** @var Price */
    protected $price;

    /** @var Url */
    protected $url;

    protected $bonus;

    protected $salesFrequency;

    protected $dateAdded;

    protected $sort;

    protected $properties = array();

    protected $attributes = array();

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

    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * @inheritdoc
     */
    public abstract function getDomSubtree(\DOMDocument $document);
}