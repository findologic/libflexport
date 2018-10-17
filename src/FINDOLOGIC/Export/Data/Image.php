<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class BaseImageMissingException extends \RuntimeException
{
    public function __construct()
    {
        $message = 'Base image without usergroup does\'t exist, exporting a “No Image Available" image is recommended!';
        parent::__construct($message);
    }
}

class ImagesWithoutUsergroupMissingException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('There exist no images without usergroup!');
    }
}

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
     * @param string $url The image url of the element.
     * @param self::TYPE_DEFAULT|self::TYPE_THUMBNAIL $type The image type to use.
     * @param string $usergroup The usergroup of the image element.
     */
    public function __construct(string $url, string $type = self::TYPE_DEFAULT, string $usergroup = '')
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

    private function setUrl(string $url)
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
