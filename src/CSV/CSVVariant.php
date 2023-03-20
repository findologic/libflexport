<?php

namespace FINDOLOGIC\Export\CSV;

use BadMethodCallException;
use DOMDocument;
use FINDOLOGIC\Export\Data\Variant;

class CSVVariant extends Variant
{
    public function getDomSubtree(DOMDocument $document)
    {
        throw new BadMethodCallException('CSVItem does not implement XML export.');
    }

    public function getCsvFragment(array $availableProperties = [])
    {
        // TODO: Implement getCsvFragment() method.
    }
}
