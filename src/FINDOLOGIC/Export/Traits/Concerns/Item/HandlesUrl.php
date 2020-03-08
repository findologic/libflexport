<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Url;

trait HandlesUrl
{
    /** @var Url */
    protected $url;

    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @param Url $url The url element to add to the item.
     */
    public function setUrl(Url $url): void
    {
        $this->url = $url;
    }

    public function addUrl(string $url, string $usergroup = ''): void
    {
        $this->url->setValue($url, $usergroup);
    }
}
