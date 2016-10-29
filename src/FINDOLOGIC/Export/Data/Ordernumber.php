<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Ordernumber implements Serializable
{
    private $value;
    private $usergroup;

    public function __construct($value, $usergroup = '')
    {
        $this->value = $value;
        $this->usergroup = $usergroup;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $ordernumberElem = XMLHelper::createElementWithText($document, 'ordernumber', $this->value);

        return $ordernumberElem;
    }
}