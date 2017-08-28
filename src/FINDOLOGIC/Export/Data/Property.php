<?php

namespace FINDOLOGIC\Export\Data;


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

    public function __construct($key, $values = array())
    {
        foreach(self::RESERVED_PROPERTY_KEYS as $reservedPropertyKey) {
            if (preg_match($reservedPropertyKey, $key)) {
                throw new PropertyKeyNotAllowedException($key);
            }
        }

        $this->key = $key;
        $this->values = $values;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function addValue($value, $usergroup = null)
    {
        if (array_key_exists($usergroup, $this->values)) {
            throw new DuplicateValueForUsergroupException($this->key, $usergroup);
        }

        $this->values[$usergroup] = $value;
    }

    public function getAllValues()
    {
        return $this->values;
    }
}