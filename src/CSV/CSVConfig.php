<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Helpers\DataHelper;

final class CSVConfig
{
    /** @var string[] */
    private array $availableProperties = [];

    /** @var string[] */
    private array $availableAttributes = [];

    public function __construct(
        array $availableProperties = [],
        array $availableAttributes = [],
        private readonly int $imageCount = 1,
    ) {
        $this->availableProperties = DataHelper::checkForInvalidCsvColumnKeys($availableProperties);
        $this->availableAttributes = DataHelper::checkForInvalidCsvColumnKeys($availableAttributes);
    }

    /**
     * @return string[]
     */
    public function getAvailableProperties(): array
    {
        return $this->availableProperties;
    }

    /**
     * @return string[]
     */
    public function getAvailableAttributes(): array
    {
        return $this->availableAttributes;
    }

    public function getImageCount(): int
    {
        return $this->imageCount;
    }
}
