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
        parent::__construct(sprintf('Property key "%s" is not allowed.', $key));
    }
}

class Property
{
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