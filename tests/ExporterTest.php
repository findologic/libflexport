<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Exporter;
use InvalidArgumentException;

class ExporterTest extends TestCase
{
    public function testItemsPerPageMustBeGreaterThanZero(): void
    {
        try {
            Exporter::create(Exporter::TYPE_XML, 0);
            $this->fail('Requesting an item count less than one should cause an exception.');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('At least one item must be exported per page.', $e->getMessage());
        }
    }

    public function testUnknownExporterTypeMustThrowException(): void
    {
        try {
            Exporter::create(123);
            $this->fail('Requesting an unknown exporter type must cause an exception.');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('Unsupported exporter type.', $e->getMessage());
        }
    }

    public function testCsvHeadingIsNotWrittenToOutputWhenStartIsNonZero(): void
    {
        $exporter = Exporter::create(Exporter::TYPE_CSV);

        $output = $exporter->serializeItems([], 1, 1, 1);

        $this->assertEquals('', $output);
    }
}
