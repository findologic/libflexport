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

    /** @var Bonus */
    protected $bonus;

    /** @var SalesFrequency */
    protected $salesFrequency;

    /** @var DateAdded */
    protected $dateAdded;

    /** @var Sort */
    protected $sort;

    /** @var AllKeywords */
    protected $keywords;

    /** @var AllOrdernumbers */
    protected $ordernumbers;

    protected $properties = array();

    protected $attributes = array();

    protected $images = array();

    protected $usergroups = array();

    public function __construct($id)
    {
        $this->id = $id;

        $this->name = new Name();
        $this->summary = new Summary();
        $this->description = new Description();
        $this->url = new Url();
        $this->bonus = new Bonus();
        $this->salesFrequency = new SalesFrequency();
        $this->dateAdded = new DateAdded();
        $this->sort = new Sort();
        $this->keywords = new AllKeywords();
        $this->ordernumbers = new AllOrdernumbers();
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

    public function addImage(Image $image)
    {
        if (!array_key_exists($image->getUsergroup(), $this->images)) {
            $this->images[$image->getUsergroup()] = array();
        }

        array_push($this->images[$image->getUsergroup()], $image);
    }

    public function setAllImages(array $images)
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }
    }

    public function addOrdernumber(Ordernumber $ordernumber)
    {
        $this->ordernumbers->addValue($ordernumber);
    }

    public function setAllOrdernumbers(array $ordernumbers)
    {
        $this->ordernumbers->setAllValues($ordernumbers);
    }

    public function addKeyword(Keyword $keyword)
    {
        $this->keywords->addValue($keyword);
    }

    public function setAllKeywords(array $keywords)
    {
        $this->keywords->setAllValues($keywords);
    }

    public function addUsergroup(Usergroup $usergroup)
    {
        array_push($this->usergroups, $usergroup);
    }

    public function setAllUsergroups(array $usergroups)
    {
        $this->usergroups = $usergroups;
    }

    /**
     * @inheritdoc
     */
    public abstract function getDomSubtree(\DOMDocument $document);
}