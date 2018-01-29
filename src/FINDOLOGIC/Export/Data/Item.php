<?php

namespace FINDOLOGIC\Export\Data;

use DateTime;
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

    protected $properties = [];

    protected $attributes = [];

    protected $images = [];

    protected $usergroups = [];

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

    public function getName()
    {
        return $this->name;
    }

    public function setName(Name $name)
    {
        $this->name = $name;
    }

    public function addName($name, $usergroup = '')
    {
        $this->name->setValue($name, $usergroup);
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary(Summary $summary)
    {
        $this->summary = $summary;
    }

    public function addSummary($summary, $usergroup = '')
    {
        $this->summary->setValue($summary, $usergroup);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(Description $description)
    {
        $this->description = $description;
    }

    public function addDescription($description, $usergroup = '')
    {
        $this->description->setValue($description, $usergroup);
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(Price $price)
    {
        $this->price = $price;
    }

    public function addPrice($price, $usergroup = '')
    {
        if ($this->price === null) {
            $this->price = new Price();
        }

        $this->price->setValue($price, $usergroup);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl(Url $url)
    {
        $this->url = $url;
    }

    public function addUrl($url, $usergroup = '')
    {
        $this->url->setValue($url, $usergroup);
    }

    public function getBonus()
    {
        return $this->bonus;
    }

    public function setBonus(Bonus $bonus)
    {
        $this->bonus = $bonus;
    }

    public function addBonus($bonus, $usergroup = '')
    {
        $this->bonus->setValue($bonus, $usergroup);
    }

    public function getSalesFrequency()
    {
        return $this->salesFrequency;
    }

    public function setSalesFrequency(SalesFrequency $salesFrequency)
    {
        $this->salesFrequency = $salesFrequency;
    }

    public function addSalesFrequency($salesFrequency, $usergroup = '')
    {
        $this->salesFrequency->setValue($salesFrequency, $usergroup);
    }

    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    public function setDateAdded(DateAdded $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    public function addDateAdded(DateTime $dateAdded, $usergroup = '')
    {
        $this->dateAdded->setDateValue($dateAdded, $usergroup);
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setSort(Sort $sort)
    {
        $this->sort = $sort;
    }

    public function addSort($sort, $usergroup = '')
    {
        $this->sort->setValue($sort, $usergroup);
    }

    public function addProperty(Property $property)
    {
        foreach ($property->getAllValues() as $usergroup => $value) {
            if (!array_key_exists($usergroup, $this->properties)) {
                $this->properties[$usergroup] = [];
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
            $this->images[$image->getUsergroup()] = [];
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
    abstract public function getDomSubtree(\DOMDocument $document);
}
