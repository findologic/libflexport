<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;

abstract class Variant implements Serializable
{
    protected string $id;

    protected Name $name;

    protected ?Price $price = null;

    protected ?OverriddenPrice $overriddenPrice = null;

    protected AllOrdernumbers $ordernumbers;

    /** @var Property[] */
    protected array $properties = [];

    /** @var Attribute[] */
    protected array $attributes = [];

    /** @var Group[] */
    protected array $groups = [];

    public function __construct($id)
    {
        $this->setId($id);

        $this->name = new Name();
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

    public function addName(string $name, string $usergroup = ''): void
    {
        $this->name->setValue($name, $usergroup);
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function addPrice(string|int|float $price): void
    {
        if ($this->price === null) {
            $this->price = new Price();
        }

        $this->price->setValue($price);
    }

    public function getOverriddenPrice(): OverriddenPrice
    {
        return $this->overriddenPrice;
    }

    public function setOverriddenPrice(OverriddenPrice $overriddenPrice): void
    {
        $this->overriddenPrice = $overriddenPrice;
    }

    public function addOverriddenPrice(string|int|float $overriddenPrice): void
    {
        if ($this->overriddenPrice === null) {
            $this->overriddenPrice = new OverriddenPrice();
        }

        $this->overriddenPrice->setValue($overriddenPrice);
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

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    public function setAllGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * @inheritdoc
     */
    abstract public function getDomSubtree(DOMDocument $document);
}
