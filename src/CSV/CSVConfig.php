<?php

namespace FINDOLOGIC\Export\CSV;

use FINDOLOGIC\Export\Helpers\DataHelper;

class CSVConfig
{
    /** @var string[] */
    protected array $availableProperties;

    /** @var string[] */
    protected array $availableAttributes;

    protected int $imageCount;

    public function __construct(
        array $availableProperties = [],
        array $availableAttributes = [],
        int $imageCount = 1,
    ) {
        $this->availableProperties = DataHelper::checkForInvalidCsvColumnKeys($availableProperties);
        $this->availableAttributes = DataHelper::checkForInvalidCsvColumnKeys($availableAttributes);
        $this->imageCount = $imageCount;
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
