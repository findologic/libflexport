<?php

namespace FINDOLOGIC\Export\Data;


class DuplicateValueForUsergroupException extends \RuntimeException
{
    public function __construct($key, $usergroup)
    {
        parent::__construct(sprintf('Property "%s" already has a value for usergroup "%s".', $key, $usergroup));
    }
}

class Property
{
    private $key;
    private $values;

    public function __construct($key, $values = array())
    {
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