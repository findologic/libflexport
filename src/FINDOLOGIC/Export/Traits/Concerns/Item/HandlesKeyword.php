<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\AllKeywords;
use FINDOLOGIC\Export\Data\Keyword;

trait HandlesKeyword
{
    /** @var AllKeywords */
    protected $keywords;

    /**
     * @param Keyword $keyword The keyword element to add to the item.
     */
    public function addKeyword(Keyword $keyword): void
    {
        $this->keywords->addValue($keyword);
    }

    /**
     * @param array $keywords Array of keyword elements which should be added to the item.
     */
    public function setAllKeywords(array $keywords): void
    {
        $this->keywords->setAllValues($keywords);
    }
}
