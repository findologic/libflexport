<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Image implements Serializable
{
    /**
     * Main, full-size image type.
     */
    const TYPE_DEFAULT = '';

    /**
     * Scaled-down thumbnail image type.
     */
    const TYPE_THUMBNAIL = 'thumbnail';

    private $url;
    private $type;
    private $usergroup;

    public function __construct($url, $type = self::TYPE_DEFAULT, $usergroup = '')
    {
        $this->url = $url;
        $this->type = $type;
        $this->usergroup = $usergroup;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
        $imageElem = XMLHelper::createElementWithText($document, 'image', $this->url);
        if ($this->type) {
            $imageElem->setAttribute('type', $this->type);
        }

        return $imageElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment()
    {
        return $this->url;
    }
}