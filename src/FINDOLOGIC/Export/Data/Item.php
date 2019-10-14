<?php

namespace FINDOLOGIC\Export\Data;

use DateTimeInterface;
use DOMDocument;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\DataHelper;
use InvalidArgumentException;

abstract class Item implements Serializable
{
    /** @var string */
    protected $id;

    /** @var Name */
    protected $name;

    /** @var Summary */
    protected $summary;

    /** @var Description */
    protected $description;

    /** @var Price */
    protected $price;

    /** @var float */
    protected $insteadPrice;

    /** @var float */
    protected $maxPrice;

    /** @var float */
    protected $taxRate;

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
        $this->setId($id);

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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id The id of the item to set
     */
    public function setId(string $id): void
    {
        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
        $this->id = $id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param Name $name The name element to add to the item.
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    /**
     * Shortcut to easily add the name of the item.
     *
     * @param string $name The name of the item.
     * @param string $usergroup The usergroup of the name element.
     */
    public function addName(string $name, string $usergroup = ''): void
    {
        $this->name->setValue($name, $usergroup);
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    /**
     * @param Summary $summary The summary element to add to the item.
     */
    public function setSummary(Summary $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Shortcut to easily add the summary of the item.
     *
     * @param string $summary The summary of the item.
     * @param string $usergroup The usergroup of the summary.
     */
    public function addSummary(string $summary, string $usergroup = ''): void
    {
        $this->summary->setValue($summary, $usergroup);
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @param Description $description The description element to add to the item.
     */
    public function setDescription(Description $description): void
    {
        $this->description = $description;
    }

    /**
     * Shortcut to easily add the description of the item.
     *
     * @param string $description The description of the item.
     * @param string $usergroup The usergroup of the description.
     */
    public function addDescription(string $description, string $usergroup = ''): void
    {
        $this->description->setValue($description, $usergroup);
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price The price element to add to the item.
     */
    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    /**
     * Shortcut to easily add the price of the item.
     *
     * @param string $price The price of the item.
     * @param string $usergroup The usergroup of the price.
     */
    public function addPrice($price, $usergroup = ''): void
    {
        if ($this->price === null) {
            $this->price = new Price();
        }

        $this->price->setValue($price, $usergroup);
    }

    /**
     * @param Price[] $prices
     */
    public function setAllPrices(array $prices): void
    {
        foreach ($prices as $price) {
            if (!$price instanceof Price) {
                throw new InvalidArgumentException(sprintf(
                    'Given prices must be instances of %s',
                    Price::class
                ));
            }

            foreach ($price->getValues() as $usergroup => $value) {
                $this->addPrice($value, $usergroup);
            }
        }
    }

    public function getInsteadPrice()
    {
        return $this->insteadPrice;
    }

    /**
     * Set the instead price of the item. This is only relevant for CSV export type.
     *
     * @param float $insteadPrice The instead price of the item.
     */
    public function setInsteadPrice(float $insteadPrice): void
    {
        $this->insteadPrice = $insteadPrice;
    }

    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set the max price of the item. This is only relevant for CSV export type.
     *
     * @param float $maxPrice The instead price of the item.
     */
    public function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set the tax rate of the item. This is only relevant for CSV export type.
     *
     * @param float $taxRate The tax rate of the item.
     */
    public function setTaxRate(float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @param Url $url The url element to add to the item.
     */
    public function setUrl(Url $url): void
    {
        $this->url = $url;
    }

    public function addUrl(string $url, string $usergroup = ''): void
    {
        $this->url->setValue($url, $usergroup);
    }

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    /**
     * @param Bonus $bonus The bonus element to add to the item.
     */
    public function setBonus(Bonus $bonus): void
    {
        $this->bonus = $bonus;
    }

    /**
     * Shortcut to easily add the bonus of the item. The value must be a numeric.
     *
     * @param float $bonus The bonus value of the item.
     * @param string $usergroup The usergroup of the bonus value.
     */
    public function addBonus(float $bonus, string $usergroup = ''): void
    {
        $this->bonus->setValue($bonus, $usergroup);
    }

    public function getSalesFrequency(): SalesFrequency
    {
        return $this->salesFrequency;
    }

    /**
     * @param SalesFrequency $salesFrequency The sales frequency element to add to the item.
     */
    public function setSalesFrequency(SalesFrequency $salesFrequency): void
    {
        $this->salesFrequency = $salesFrequency;
    }

    /**
     * Shortcut to easily add the sales frequency of the item. The value must be a positive integer.
     *
     * @param int $salesFrequency The sales frequency of the item.
     * @param string $usergroup The usergroup of the sales frequency.
     */
    public function addSalesFrequency(int $salesFrequency, string $usergroup = ''): void
    {
        $this->salesFrequency->setValue($salesFrequency, $usergroup);
    }

    public function getDateAdded(): DateAdded
    {
        return $this->dateAdded;
    }

    /**
     * @param DateAdded $dateAdded The date added element to add to the item.
     */
    public function setDateAdded(DateAdded $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * Shortcut to easily add the date added value of the item.
     *
     * @param DateTimeInterface $dateAdded The date on which the item was added to the ecommerce system.
     * @param string $usergroup The usergroup of the date added value.
     */
    public function addDateAdded(DateTimeInterface $dateAdded, string $usergroup = ''): void
    {
        $this->dateAdded->setDateValue($dateAdded, $usergroup);
    }

    public function getSort(): Sort
    {
        return $this->sort;
    }

    /**
     * @param Sort $sort The sort element to add to the item.
     */
    public function setSort(Sort $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * Shortcut to easily add the sort value of the item.
     *
     * @param int $sort The sort value of the item.
     * @param string $usergroup The usergroup of the sort value.
     */
    public function addSort(int $sort, string $usergroup = ''): void
    {
        $this->sort->setValue($sort, $usergroup);
    }

    /**
     * @param Property $property The property element to add to the item.
     */
    public function addProperty(Property $property): void
    {
        if (count($property->getAllValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Property', $property->getKey());
        }

        foreach ($property->getAllValues() as $usergroup => $value) {
            // No need to check if there are duplicate values for a single property and usergroup, because
            // Property::addValue() already takes care of that.

            $this->properties[$usergroup][$property->getKey()] = $value;
        }
    }

    /**
     * Adds an attribute.
     *
     * E.g.
     * ```
     * $attr1 = Attribute('color', ['orange', 'yellow']);
     * $attr2 = Attribute('color', ['pink', 'orange']);
     *
     * $item->addAttribute($attr1);
     * $item->addAttribute($attr2);
     * // $item attributes will be: ['pink', 'orange']
     * ```
     *
     * @see addMergedAttribute if you want to merge values with the same key.
     * @param Attribute $attribute The attribute element to add to the item.
     */
    public function addAttribute(Attribute $attribute): void
    {
        if (count($attribute->getValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Attribute', $attribute->getKey());
        }

        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * Adds an attribute by merging attribute values with the same key.
     *
     * E.g.
     * ```
     * $attr1 = Attribute('color', ['orange', 'yellow']);
     * $attr2 = Attribute('color', ['pink', 'orange']);
     *
     * $item->addAttribute($attr1);
     * $item->addAttribute($attr2);
     * // $item attributes will be: ['orange', 'yellow', 'pink']
     * ```
     *
     * @see addAttribute if you don't want to merge values with the same key.
     */
    public function addMergedAttribute(Attribute $attribute): void
    {
        if (count($attribute->getValues()) === 0) {
            throw new EmptyElementsNotAllowedException('Attribute', $attribute->getKey());
        }

        if (!isset($this->attributes[$attribute->getKey()])) {
            $this->attributes[$attribute->getKey()] = $attribute;
            return;
        }

        $this->attributes[$attribute->getKey()] = new Attribute(
            $attribute->getKey(),
            array_unique(array_merge($this->attributes[$attribute->getKey()]->getValues(), $attribute->getValues()))
        );
    }

    /**
     * @param Image $image The image element to add to the item.
     */
    public function addImage(Image $image): void
    {
        if (!array_key_exists($image->getUsergroup(), $this->images)) {
            $this->images[$image->getUsergroup()] = [];
        }

        array_push($this->images[$image->getUsergroup()], $image);
    }

    /**
     * @param array $images Array of image elements which should be added to the item.
     */
    public function setAllImages(array $images): void
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }
    }

    /**
     * @param Ordernumber $ordernumber The ordernumber element to add to the item.
     */
    public function addOrdernumber(Ordernumber $ordernumber): void
    {
        $this->ordernumbers->addValue($ordernumber);
    }

    /**
     * @param array $ordernumbers Array of ordernumber elements which should be added to the item.
     */
    public function setAllOrdernumbers(array $ordernumbers): void
    {
        $this->ordernumbers->setAllValues($ordernumbers);
    }

    /**
     * @param Keyword $keyword The keyword element to add to the item.
     */
    public function addKeyword(Keyword $keyword): void
    {
        $this->keywords->addValue($keyword);
    }

    /**
     * @param array $keywords Array of keyword elements which should be added to the item.
     */
    public function setAllKeywords(array $keywords): void
    {
        $this->keywords->setAllValues($keywords);
    }

    /**
     * @param Usergroup $usergroup The usergroup element to add to the item.
     */
    public function addUsergroup(Usergroup $usergroup): void
    {
        array_push($this->usergroups, $usergroup);
    }

    /**
     * @param array $usergroups Array of usergroup elements which should be added to the item.
     */
    public function setAllUsergroups(array $usergroups): void
    {
        $this->usergroups = $usergroups;
    }

    /**
     * @inheritdoc
     */
    abstract public function getDomSubtree(DOMDocument $document);
}
