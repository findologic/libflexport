<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Summary;

trait HasSummary
{
    use SupportsUserGroups;

    protected Summary $summary;

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function setSummary(Summary $summary): void
    {
        $this->checkUsergroupAwareValue($summary);

        $this->summary = $summary;
    }

    public function addSummary(string $summary, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->summary->setValue($summary, $usergroup);
    }
}
