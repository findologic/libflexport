<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Summary;

trait HandlesSummary
{
    /** @var Summary */
    protected $summary;

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    /**
     * @param Summary $summary The summary element to add to the item.
     */
    public function setSummary(Summary $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Shortcut to easily add the summary of the item.
     *
     * @param string $summary The summary of the item.
     * @param string $usergroup The usergroup of the summary.
     */
    public function addSummary(string $summary, string $usergroup = ''): void
    {
        $this->summary->setValue($summary, $usergroup);
    }
}
