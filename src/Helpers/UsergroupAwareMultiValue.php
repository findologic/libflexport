<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Helpers;

use DOMDocument;
use DOMElement;

/**
 * Class UsergroupAwareMultiValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Multi values that can differ per usergroup, and have multiple values for each.
 */
abstract class UsergroupAwareMultiValue implements Serializable, NameAwareValue
{
    protected array $values = [];

    public function __construct(
        private readonly string $rootCollectionName,
        private readonly string $usergroupCollectionName
    ) {
    }

    /**
     * @param UsergroupAwareMultiValueItem $value The element to add the the collection.
     */
    public function addValue(UsergroupAwareMultiValueItem $value): void
    {
        if (!array_key_exists($value->getUsergroup(), $this->getValues())) {
            $this->values[$value->getUsergroup()] = [];
        }

        $this->values[$value->getUsergroup()][] = $value;
    }

    /**
     * @param UsergroupAwareMultiValueItem[] $values Array of elements to be added to the collection.
     */
    public function setAllValues(array $values): void
    {
        $this->values = [];

        foreach ($values as $value) {
            $this->addValue($value);
        }
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
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
