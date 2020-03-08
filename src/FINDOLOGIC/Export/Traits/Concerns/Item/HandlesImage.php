<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Image;

trait HandlesImage
{
    protected $images = [];

    /**
     * @param Image $image The image element to add to the item.
     */
    public function addImage(Image $image): void
    {
        if (!array_key_exists($image->getUsergroup(), $this->images)) {
            $this->images[$image->getUsergroup()] = [];
        }

        array_push($this->images[$image->getUsergroup()], $image);
    }

    /**
     * @param array $images Array of image elements which should be added to the item.
     */
    public function setAllImages(array $images): void
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }
    }
}
