<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Enums\ImageType;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

final class Image implements Serializable, NameAwareValue
{
    private string $url;

    /**
     * @param string $url The image url of the element.
     * @param ImageType $type The image type to use. Either ImageType::DEFAULT or ImageType::THUMBNAIL.
     * @param string $usergroup The usergroup of the image element.
     */
    public function __construct(
        string $url,
        private readonly ImageType $type = ImageType::DEFAULT,
        private readonly string $usergroup = ''
    ) {
        $this->setUrl($url);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    private function setUrl(string $url): void
    {
        $this->url = DataHelper::checkForEmptyValue($this->getValueName(), $url);
    }

    public function getType(): ImageType
    {
        return $this->type;
    }

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
        if ($this->getType() !== ImageType::DEFAULT) {
            $imageElem->setAttribute('type', $this->getType()->value);
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
