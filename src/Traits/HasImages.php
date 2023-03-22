<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Image;

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
}
