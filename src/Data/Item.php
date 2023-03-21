<?php

namespace FINDOLOGIC\Export\Data;

use DateTimeInterface;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;
use InvalidArgumentException;

abstract class Item implements Serializable
{
    protected string $id;

    protected Name $name;

    protected Summary $summary;

    protected Description $description;

    protected Price $price;

    protected OverriddenPrice $overriddenPrice;

    protected Url $url;

    protected Bonus $bonus;

    protected SalesFrequency $salesFrequency;

    protected DateAdded $dateAdded;

    protected Sort $sort;

    protected AllKeywords $keywords;

    protected AllOrdernumbers $ordernumbers;

    /** @var array<string, Property[]> */
    protected array $properties = [];

    /** @var Attribute[] */
    protected array $attributes = [];

    /** @var array<string, Image[]> */
    protected array $images = [];

    /** @var Group[]  */
    protected array $groups = [];

    /** @var Variant[] */
    protected array $variants = [];

    public function __construct($id)
    {
        $this->setId($id);

        $this->name = new Name();
        $this->summary = new Summary();
        $this->description = new Description();
        $this->price = new Price();
        $this->overriddenPrice = new OverriddenPrice();
        $this->url = new Url();
        $this->bonus = new Bonus();
        $this->salesFrequency = new SalesFrequency();
        $this->dateAdded = new DateAdded();
        $this->sort = new Sort();
        $this->keywords = new AllKeywords();
        $this->ordernumbers = new AllOrdernumbers();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
        $this->id = $id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function addName(string $name, string $usergroup = ''): void
    {
        $this->name->setValue($name, $usergroup);
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function setSummary(Summary $summary): void
    {
        $this->summary = $summary;
    }

    public function addSummary(string $summary, string $usergroup = ''): void
    {
        $this->summary->setValue($summary, $usergroup);
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function setDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function addDescription(string $description, string $usergroup = ''): void
    {
        $this->description->setValue($description, $usergroup);
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function addPrice(string|int|float $price, string $usergroup = ''): void
    {
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

    public function getOverriddenPrice(): OverriddenPrice
    {
        return $this->overriddenPrice;
    }

    public function setOverriddenPrice(OverriddenPrice $overriddenPrice): void
    {
        $this->overriddenPrice = $overriddenPrice;
    }

    public function addOverriddenPrice(string|int|float $overriddenPrice, string $usergroup = ''): void
    {
        if ($this->overriddenPrice === null) {
            $this->overriddenPrice = new OverriddenPrice();
        }

        $this->overriddenPrice->setValue($overriddenPrice, $usergroup);
    }

    /**
     * @param OverriddenPrice[] $overriddenPrices
     */
    public function setAllOverriddenPrices(array $overriddenPrices): void
    {
        foreach ($overriddenPrices as $overriddenPrice) {
            if (!$overriddenPrice instanceof OverriddenPrice) {
                throw new InvalidArgumentException(sprintf(
                    'Given overridden prices must be instances of %s',
                    OverriddenPrice::class
                ));
            }

            foreach ($overriddenPrice->getValues() as $usergroup => $value) {
                $this->addOverriddenPrice($value, $usergroup);
            }
        }
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

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

    public function setBonus(Bonus $bonus): void
    {
        $this->bonus = $bonus;
    }

    public function addBonus(float $bonus, string $usergroup = ''): void
    {
        $this->bonus->setValue($bonus, $usergroup);
    }

    public function getSalesFrequency(): SalesFrequency
    {
        return $this->salesFrequency;
    }

    public function setSalesFrequency(SalesFrequency $salesFrequency): void
    {
        $this->salesFrequency = $salesFrequency;
    }

    public function addSalesFrequency(int $salesFrequency, string $usergroup = ''): void
    {
        $this->salesFrequency->setValue($salesFrequency, $usergroup);
    }

    public function getDateAdded(): DateAdded
    {
        return $this->dateAdded;
    }

    public function setDateAdded(DateAdded $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    public function addDateAdded(DateTimeInterface $dateAdded, string $usergroup = ''): void
    {
        $this->dateAdded->setDateValue($dateAdded, $usergroup);
    }

    public function getSort(): Sort
    {
        return $this->sort;
    }

    public function setSort(Sort $sort): void
    {
        $this->sort = $sort;
    }

    public function addSort(int $sort, string $usergroup = ''): void
    {
        $this->sort->setValue($sort, $usergroup);
    }

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

        if (array_key_exists($attribute->getKey(), $this->attributes)) {
            $attribute = new Attribute(
                $attribute->getKey(),
                array_unique(array_merge($this->attributes[$attribute->getKey()]->getValues(), $attribute->getValues()))
            );
        }

        $this->attributes[$attribute->getKey()] = $attribute;
    }

    public function addImage(Image $image): void
    {
        if (!array_key_exists($image->getUsergroup(), $this->images)) {
            $this->images[$image->getUsergroup()] = [];
        }

        $this->images[$image->getUsergroup()][] = $image;
    }

    /**
     * @param Image[] $images
     */
    public function setAllImages(array $images): void
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }
    }

    public function addOrdernumber(Ordernumber $ordernumber): void
    {
        $this->ordernumbers->addValue($ordernumber);
    }

    /**
     * @param Ordernumber[] $ordernumbers
     */
    public function setAllOrdernumbers(array $ordernumbers): void
    {
        $this->ordernumbers->setAllValues($ordernumbers);
    }

    public function addKeyword(Keyword $keyword): void
    {
        $this->keywords->addValue($keyword);
    }

    /**
     * @param Keyword[] $keywords
     */
    public function setAllKeywords(array $keywords): void
    {
        $this->keywords->setAllValues($keywords);
    }

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    /**
     * @param Group[] $groups
     */
    public function setAllGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    public function addVariant(Variant $variant): void
    {
        $this->variants[] = $variant;
    }

    public function setAllVariants(array $variants): void
    {
        $this->variants = $variants;
    }

    /**
     * @inheritdoc
     */
    abstract public function getDomSubtree(DOMDocument $document): DOMElement;
}
