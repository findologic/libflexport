<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Helpers\DataHelper;

final class CSVVariant extends Variant
{
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        throw new BadMethodCallException('CSVVariant does not implement XML export.');
    }

    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        return sprintf(
            CSVExporter::LINE_TEMPLATE,
            $this->getId(),
            $this->getParentId(),
            $this->buildCsvOrdernumbers($csvConfig),
            DataHelper::sanitize($this->name->getCsvFragment($csvConfig)),
            '', // summary
            '', // description
            $this->price->getCsvFragment($csvConfig),
            $this->getOverriddenPrice()->getCsvFragment($csvConfig),
            DataHelper::sanitize($this->url->getCsvFragment($csvConfig)),
            '', // keywords
            $this->buildCsvGroups($csvConfig),
            '', // bonus
            '', // salesFrequency
            '', // dateAdded
            '', // sort
            '', // visibility
            $this->buildCsvImages($csvConfig),
            $this->buildCsvProperties($csvConfig),
            $this->buildCsvAttributes($csvConfig),
        );
    }
}
