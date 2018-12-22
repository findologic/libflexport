<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;
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

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct($url, $type = self::TYPE_DEFAULT, $usergroup = '')
    {
        $this->setUrl($url);
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

    private function setUrl($url)
    {
        $url = DataHelper::checkForEmptyValue($url);

        $this->url = $url;
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
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $imageElem = XMLHelper::createElementWithText($document, 'image', DataHelper::validateUrl($this->getUrl()));
        if ($this->getType()) {
            $imageElem->setAttribute('type', $this->getType());
        }

        return $imageElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(array $availableProperties = [])
    {
        return $this->getUrl();
    }
}
