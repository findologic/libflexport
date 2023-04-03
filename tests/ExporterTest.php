<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Exporter;
use InvalidArgumentException;

final class ExporterTest extends TestCase
{
    public function testItemsPerPageMustBeGreaterThanZero(): void
    {
        try {
            Exporter::create(ExporterType::XML, 0);
            $this->fail('Requesting an item count less than one should cause an exception.');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('At least one item must be exported per page.', $e->getMessage());
        }
    }

    public function testCsvHeadingIsNotWrittenToOutputWhenStartIsNonZero(): void
    {
        $exporter = Exporter::create(ExporterType::CSV);

        $output = $exporter->serializeItems([], 1, 1, 1);

        $this->assertEquals('', $output);
    }
}
