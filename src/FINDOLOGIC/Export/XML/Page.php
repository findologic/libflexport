<?php

namespace FINDOLOGIC\Export\XML;

use DOMDocument;
use FINDOLOGIC\Export\Constant;
use FINDOLOGIC\Export\Exceptions\ItemsExceedCountValueException;
use FINDOLOGIC\Export\Exceptions\XMLSchemaViolationException;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Page
{
    private $items;
    private $start;
    private $count;
    private $total;

    public function __construct(int $start, int $count, int $total)
    {
        $this->start = $start;
        $this->count = $count;
        $this->total = $total;
        $this->items = [];
    }

    public function addItem(XMLItem $item): void
    {
        array_push($this->items, $item);
    }

    public function setAllItems(array $items): void
    {
        $this->items = [];

        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getXml(): DOMDocument
    {
        if (count($this->items) > $this->count) {
            throw new ItemsExceedCountValueException();
        }

        $document = new DOMDocument('1.0', 'utf-8');
        $root = XMLHelper::createElement($document, 'findologic', ['version' => '1.0']);
        $document->appendCHild($root);

        $items = XMLHelper::createElement($document, 'items', [
            'start' => $this->start,
            'count' => $this->count,
            'total' => $this->total
        ]);
        $root->appendChild($items);

        /** @var XMLItem $item */
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
        set_error_handler(function ($errno, $errstr) use (&$validationErrors) {
            array_push($validationErrors, $errstr);
        });

        $isValid = $document->schemaValidate(Constant::$XSD_SCHEMA_PATH);
        restore_error_handler();

        if (!$isValid) {
            throw new XMLSchemaViolationException($validationErrors);
        }
    }
}
