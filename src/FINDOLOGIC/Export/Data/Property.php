<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Exceptions\DuplicateValueForUsergroupException;
use FINDOLOGIC\Export\Exceptions\PropertyKeyNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;

class Property
{
    /**
     * Reserved property keys for internal use which would be overwritten when importing
     *
     * /image\d+/: Image URLs of type default.
     * /thumbnail\d+/: Image URLs of type thumbnail.
     * /ordernumber/: The products first exported ordernumber.
     */
    private const RESERVED_PROPERTY_KEYS = [
        "/image\d+/",
        "/thumbnail\d+/",
        "/ordernumber/"
    ];

    /** @var string */
    private $key;

    /** @var array */
    private $values;

    /**
     * Property constructor.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $key The property key.
     * @param array $values The values of the property.
     */
    public function __construct(string $key, array $values = [])
    {
        foreach (self::RESERVED_PROPERTY_KEYS as $reservedPropertyKey) {
            if (preg_match($reservedPropertyKey, $key)) {
                throw new PropertyKeyNotAllowedException($key);
            }
        }

        $this->key = DataHelper::checkForEmptyValue('propertyKey', $key);
        $this->setValues($values);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Add a value to the property element.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $value The value to add to the property element.
     * @param string|null $usergroup The usergroup of the property value.
     */
    public function addValue(string $value, ?string $usergroup = null): void
    {
        if (array_key_exists($usergroup, $this->getAllValues())) {
            throw new DuplicateValueForUsergroupException($this->getKey(), $usergroup);
        }

        $this->values[$usergroup] = DataHelper::checkForEmptyValue('propertyValue', $value);
    }

    protected function setValues(array $values): void
    {
        $this->values = [];

        /**
         * As we can not check if the values of the given array are associative,
         * we trigger a notice if the array keys are not a string.
         */
        array_walk($values, function (/** @noinspection PhpUnusedParameterInspection */ $item, $key) {
            if (!is_string($key)) {
                $format = 'Property values have to be associative, like $key => $value. The key "%s" has to be a ' .
                    'string, integer given.';
                trigger_error(sprintf($format, $key), E_USER_WARNING);
            }
        });

        foreach ($values as $usergroup => $value) {
            $this->addValue($value, $usergroup);
        }
    }

    public function getAllValues(): array
    {
        return $this->values;
    }
}
