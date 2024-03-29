<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export;

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\CSV\CSVExporter;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\XML\XMLExporter;
use InvalidArgumentException;

abstract class Exporter
{
    /** @var string */
    protected const DEFAULT_FILE_NAME_PREFIX = 'findologic';

    protected string $fileNamePrefix = self::DEFAULT_FILE_NAME_PREFIX;

    protected function __construct(protected int $itemsPerPage)
    {
    }

    /**
     * Creates an exporter for the desired output format.
     *
     * @param ExporterType $type The type of export format to choose.
     *      Must be either ExporterType::XML or ExporterType::CSV.
     * @param int $itemsPerPage Number of items being exported at once. Respecting this parameter is at the exporter
     *      implementation's discretion.
     * @return Exporter The exporter for the desired output format.
     */
    public static function create(ExporterType $type, int $itemsPerPage = 20, ?CSVConfig $csvConfig = null): Exporter
    {
        if ($itemsPerPage < 1) {
            throw new InvalidArgumentException('At least one item must be exported per page.');
        }

        return match ($type) {
            ExporterType::XML => new XMLExporter($itemsPerPage),
            ExporterType::CSV => new CSVExporter($itemsPerPage, $csvConfig ?? new CSVConfig()),
        };
    }

    /**
     * Can be used to alter the file name of the serialization output. Default: "findologic".
     *
     * E.g.
     * * XMLExporter: `<path>/<fileNamePrefix>_<start>_<count>.xml`
     * * CSVExporter: `<path>/<fileNamePrefix>.csv`
     */
    public function setFileNamePrefix(string $fileNamePrefix = self::DEFAULT_FILE_NAME_PREFIX): self
    {
        $this->fileNamePrefix = $fileNamePrefix;

        return $this;
    }

    /**
     * Turns the provided items into their serialized form.
     *
     * @param Item[] $items Array of items to serialize. All of them are serialized, regardless of $start and $total.
     * @param int $start Assuming that $items is a fragment of the total, this is the global index of the first item in
     *      $items.
     * @param int $count The number of items requested for this export step. Actual number of items can be smaller due
     *      to errors, and can not be greater than the requested count, because that would indicate that the requested
     *      count is ignored when generating items. This value is ignored when using CSV exporter.
     * @param int $total The global total of items that could be exported. This value is ignored when using CSV
     *      exporter.
     * @return string The items in serialized form.
     */
    abstract public function serializeItems(array $items, int $start, int $count, int $total): string;

    /**
     * Like serializeItems(), but the output is written to filesystem instead of being returned.
     *
     * @param string $targetDirectory The directory to which the file is written. The filename is at the exporter
     *      implementation's discretion.
     * @param Item[] $items Array of items to serialize. All of them are serialized, regardless of $start and $total.
     * @param int $start Assuming that $items is a fragment of the total, this is the global index of the first item in
     *      $items.
     * @param int $count The number of items requested for this export step. Actual number of items can be smaller due
     *      to errors, and can not be greater than the requested count, because that would indicate that the requested
     *      count is ignored when generating items. This value is ignored when using CSV exporter.
     * @param int $total The global total of items that could be exported. This value is ignored when using CSV
     *      exporter.
     * @return string Full path of the written file.
     */
    abstract public function serializeItemsToFile(
        string $targetDirectory,
        array $items,
        int $start,
        int $count,
        int $total
    ): string;

    /**
     * Creates an export format-specific item instance.
     *
     * @param string $id Unique ID of the item.
     * @return Item The newly generated item.
     */
    abstract public function createItem(string $id): Item;

    /**
     * Creates an export format-specific variant instance.
     *
     * @param string $id Unique ID of the item.
     * @return Variant The newly generated item.
     */
    abstract public function createVariant(string $id, string $parentId): Variant;
}
