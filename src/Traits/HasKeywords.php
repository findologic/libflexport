<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\AllKeywords;
use FINDOLOGIC\Export\Data\Keyword;

trait HasKeywords
{
    use SupportsUserGroups;

    protected AllKeywords $keywords;

    public function getKeywords(): AllKeywords
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): void
    {
        $this->checkUsergroupString($keyword->getUsergroup());

        $this->keywords->addValue($keyword);
    }

    /**
     * @param Keyword[] $keywords
     */
    public function setAllKeywords(array $keywords): void
    {
        foreach ($keywords as $keyword) {
            $this->checkUsergroupString($keyword->getUsergroup());
        }

        $this->keywords->setAllValues($keywords);
    }
}
