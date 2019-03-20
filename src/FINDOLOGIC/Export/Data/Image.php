<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Image implements Serializable
{
    /**
     * Main, full-size image type.
     */
    public const TYPE_DEFAULT = '';

    /**
     * Scaled-down thumbnail image type.
     */
    public const TYPE_THUMBNAIL = 'thumbnail';

    /** @var string */
    private $url;

    /** @var string */
    private $type;

    /** @var string */
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
    public function getUrl(): string
    {
        return $this->url;
    }

    private function setUrl(string $url): void
    {
        $url = DataHelper::checkForEmptyValue($url);

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
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
    public function getCsvFragment(array $availableProperties = []): string
    {
        return $this->getUrl();
    }
}
