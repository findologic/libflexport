<?php

namespace FINDOLOGIC\Export\Traits;

use DOMDocument;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Exceptions\UsergroupsNotAllowedException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Helpers\XMLHelper;

trait HasVariants
{
    /** @var Variant[] */
    protected array $variants = [];

    protected bool $usergroupsUsed = false;

    /**
     * @return Variant[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(Variant $variant): void
    {
        if ($this->usergroupsUsed) {
            throw new UsergroupsNotAllowedException();
        }

        $this->variants[] = $variant;
    }

    public function setAllVariants(array $variants): void
    {
        if ($this->usergroupsUsed) {
            throw new UsergroupsNotAllowedException();
        }

        $this->variants = $variants;
    }

    public function checkUsergroupString(string $usergroup): void
    {
        if (strlen($usergroup)) {
            $this->usergroupsUsed = true;

            if (count($this->variants)) {
                throw new UsergroupsNotAllowedException();
            }
        }
    }

    public function checkUsergroupAwareValue(UsergroupAwareSimpleValue|UsergroupAwareMultiValue $value): void
    {
        if ($value->hasUsergroup()) {
            $this->usergroupsUsed = true;

            if (count($this->variants)) {
                throw new UsergroupsNotAllowedException();
            }
        }
    }

    public function buildXmlVariants(DOMDocument $document): \DOMElement
    {
        $variants = XMLHelper::createElement($document, 'variants');

        foreach ($this->variants as $variant) {
            $variants->appendChild($variant->getDomSubtree($document));
        }

        return $variants;
    }
}
