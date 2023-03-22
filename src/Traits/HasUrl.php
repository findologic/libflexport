<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Url;

trait HasUrl
{
    use SupportsUserGroups;

    protected Url $url;

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function setUrl(Url $url): void
    {
        $this->checkUsergroupAwareValue($url);

        $this->url = $url;
    }

    public function addUrl(string $url, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->url->setValue($url, $usergroup);
    }
}
