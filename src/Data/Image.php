<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Image implements Serializable, NameAwareValue
{
    /**
     * Main, full-size image type.
     */
    public const TYPE_DEFAULT = '';

    /**
     * Scaled-down thumbnail image type.
     */
    public const TYPE_THUMBNAIL = 'thumbnail';

    private string $url;

    private string $type;

    private string $usergroup;

    /**
     * @param string $url The image url of the element.
     * @param string $type The image type to use. Either Image::TYPE_DEFAULT or Image::TYPE_THUMBNAIL.
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
        $url = DataHelper::checkForEmptyValue($this->getValueName(), $url);

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
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        return $this->getUrl();
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'image';
    }
}
