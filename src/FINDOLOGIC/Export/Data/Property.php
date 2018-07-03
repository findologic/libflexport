<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;

class DuplicateValueForUsergroupException extends \RuntimeException
{
    public function __construct($key, $usergroup)
    {
        parent::__construct(sprintf('Property "%s" already has a value for usergroup "%s".', $key, $usergroup));
    }
}

class PropertyKeyNotAllowedException extends \RuntimeException
{
    public function __construct($key)
    {
        parent::__construct(sprintf('Property key "%s" is reserved for internal use and overwritten when importing.', $key));
    }
}

class Property
{
    /**
     * Reserved property keys for internal use which would be overwritten when importing
     *
     * /image\d+/: Image URLs of type default.
     * /thumbnail\d+/: Image URLs of type thumbnail.
     * /ordernumber/: The products first exported ordernumber.
     */
    const RESERVED_PROPERTY_KEYS = [
        "/image\d+/",
        "/thumbnail\d+/",
        "/ordernumber/"
    ];

    private $key;
    private $values;

    /**
     * Property constructor.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param string $key The property key.
     * @param array $values The values of the property.
     */
    public function __construct($key, $values = [])
    {
        foreach (self::RESERVED_PROPERTY_KEYS as $reservedPropertyKey) {
            if (preg_match($reservedPropertyKey, $key)) {
                throw new PropertyKeyNotAllowedException($key);
            }
        }

        $this->key = DataHelper::checkForEmptyValue($key);
        $this->setValues($values);
    }

    public function getKey()
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
    public function addValue($value, $usergroup = null)
    {
        if (array_key_exists($usergroup, $this->getAllValues())) {
            throw new DuplicateValueForUsergroupException($this->getKey(), $usergroup);
        }

        $this->values[$usergroup] = DataHelper::checkForEmptyValue($value);
    }

    protected function setValues($values)
    {
        $this->values = [];

        /**
         * As we can not check if the values of the given array is associative,
         * we trigger a notice if the array keys are not a string.
         */
        array_walk($values, function ($item, $key) {
            if (!is_string($key)) {
                $format = 'Property values have to be associative, like $key => $value. The key "%s" has to be a string, integer given.';
                trigger_error(sprintf($format, $key), E_USER_WARNING);
            }
        });

        foreach ($values as $usergroup => $value) {
            $this->addValue($value, $usergroup);
        }
    }

    public function getAllValues()
    {
        return $this->values;
    }
}
