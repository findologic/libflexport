<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Exporter;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    public function testItemsPerPageMustBeGreaterThanZero()
    {
        try {
            Exporter::create(Exporter::TYPE_XML, 0);
            $this->fail('Requesting an item count less than one should cause an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('At least one item must be exported per page.', $e->getMessage());
        }
    }

    public function testUnknownExporterTypeMustThrowException()
    {
        try {
            Exporter::create(123, 20);
            $this->fail('Requesting an unknown exporter type must cause an exception.');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Unsupported exporter type.', $e->getMessage());
        }
    }
}
