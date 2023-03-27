<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\XMLHelper;

trait HasGroups
{
    /** @var Group[] */
    protected array $groups = [];

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    public function setAllGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    protected function buildCsvGroups(CSVConfig $csvConfig): string
    {
        return implode(
            ',',
            array_map(
                static function (Group $group) use ($csvConfig): string {
                    $groupName = $group->getCsvFragment($csvConfig);
                    DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($groupName);
                    return DataHelper::sanitize($groupName);
                },
                $this->groups
            )
        );
    }

    protected function buildXmlGroups(DOMDocument $document): DOMElement
    {
        $groups = XMLHelper::createElement($document, 'groups');

        foreach ($this->groups as $group) {
            $groups->appendChild($group->getDomSubtree($document));
        }

        return $groups;
    }
}
