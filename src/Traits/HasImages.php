<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Enums\ImageType;
use FINDOLOGIC\Export\Exceptions\BaseImageMissingException;
use FINDOLOGIC\Export\Exceptions\ImagesWithoutUsergroupMissingException;
use FINDOLOGIC\Export\Helpers\XMLHelper;

trait HasImages
{
    use SupportsUserGroups;

    /** @var array<string, Image[]> */
    protected array $images = [];

    /**
     * @return array<string, Image[]>
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        $this->checkUsergroupString($image->getUsergroup());

        if (!array_key_exists($image->getUsergroup(), $this->images)) {
            $this->images[$image->getUsergroup()] = [];
        }

        $this->images[$image->getUsergroup()][] = $image;
    }

    /**
     * @param Image[] $images
     */
    public function setAllImages(array $images): void
    {
        foreach ($images as $image) {
            $this->checkUsergroupString($image->getUsergroup());

            $this->addImage($image);
        }
    }

    protected function buildCsvImages(CSVConfig $csvConfig): string
    {
        $imagesString = '';

        if (array_key_exists('', $this->images)) {
            $images = $this->images[''];

            for ($i = 0; $i < $csvConfig->getImageCount(); ++$i) {
                $imageUrl = isset($images[$i]) ? $images[$i]->getCsvFragment($csvConfig) : '';
                $imagesString .= "\t" . $imageUrl;
            }
        } else {
            $imagesString .= str_repeat("\t", $csvConfig->getImageCount());
        }

        return $imagesString;
    }

    protected function buildXmlImages(DOMDocument $document): DOMElement
    {
        $allImagesElem = XMLHelper::createElement($document, 'allImages');

        if ($this->images) {
            if (array_key_exists('', $this->images)) {
                foreach ($this->images as $usergroup => $images) {
                    $usergroupImagesElem = XMLHelper::createElement($document, 'images');

                    if ($usergroup) {
                        $usergroupImagesElem->setAttribute('usergroup', $usergroup);
                    }

                    $allImagesElem->appendChild($usergroupImagesElem);

                    if (self::validateImages($images)) {
                        foreach ($images as $image) {
                            $usergroupImagesElem->appendChild($image->getDomSubtree($document));
                        }
                    }
                }
            } else {
                throw new ImagesWithoutUsergroupMissingException();
            }
        }

        return $allImagesElem;
    }

    /**
     * Checks if there is at least one image of type default
     *
     * @param Image[] $images The images to validate.
     * @return boolean Whether the images are valid or not.
     * @throws BaseImageMissingException
     */
    protected static function validateImages(array $images): bool
    {
        foreach ($images as $image) {
            if ($image->getType() === ImageType::DEFAULT) {
                return true;
            }
        }

        throw new BaseImageMissingException();
    }
}
