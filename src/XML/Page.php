<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\XML;

use DOMDocument;
use FINDOLOGIC\Export\Constant;
use FINDOLOGIC\Export\Exceptions\ItemsExceedCountValueException;
use FINDOLOGIC\Export\Exceptions\XMLSchemaViolationException;
use FINDOLOGIC\Export\Helpers\XMLHelper;

final class Page
{
    /** @var XMLItem[] */
    private array $items = [];


    public function __construct(private readonly int $start, private readonly int $count, private readonly int $total)
    {
    }

    public function addItem(XMLItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param XMLItem[] $items
     */
    public function setAllItems(array $items): void
    {
        $this->items = [];

        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function getXml(): DOMDocument
    {
        if (count($this->items) > $this->count) {
            throw new ItemsExceedCountValueException();
        }

        $document = new DOMDocument('1.0', 'utf-8');
        $root = XMLHelper::createElement($document, 'findologic', ['version' => '2.0']);
        $document->appendCHild($root);

        $items = XMLHelper::createElement($document, 'items', [
            'start' => $this->start,
            'count' => $this->count,
            'total' => $this->total
        ]);
        $root->appendChild($items);

        foreach ($this->items as $item) {
            $itemDom = $item->getDomSubtree($document);
            $items->appendChild($itemDom);
        }

        $this->validateWithSchema($document);

        return $document;
    }

    /**
     * Validates the export page against the schema. In case of violations, an exception is thrown.
     *
     * @param DOMDocument $document The document to validate.
     */
    private function validateWithSchema(DOMDocument $document): void
    {
        $validationErrors = [];
        set_error_handler(static function ($errno, $errstr) use (&$validationErrors): void {
            $validationErrors[] = $errstr;
        });

        $isValid = $document->schemaValidate(Constant::$XSD_SCHEMA_PATH_20);
        restore_error_handler();

        if (!$isValid) {
            throw new XMLSchemaViolationException($validationErrors);
        }
    }
}
