<?php

namespace FINDOLOGIC\Export\XML;

use FINDOLOGIC\Export\Helpers\XMLHelper;

class ItemsExceedCountValueException extends \RuntimeException
{
    public function __construct()
    {
        $message = 'The number of items must not exceed the count value';
        parent::__construct($message);
    }
}

class Page
{
    private $items;
    private $start;
    private $count;
    private $total;

    public function __construct($start, $count, $total)
    {
        $this->start = $start;
        $this->count = $count;
        $this->total = $total;
        $this->items = array();
    }

    public function addItem(XMLItem $item)
    {
        array_push($this->items, $item);
    }

    public function setAllItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getXml()
    {
        if (count($this->items) > $this->count) {
            throw new ItemsExceedCountValueException();
        }

        $document = new \DOMDocument('1.0', 'utf-8');
        $root = XMLHelper::createElement($document, 'findologic', array('version' => '1.0'));
        $document->appendCHild($root);

        $items = XMLHelper::createElement($document, 'items', array(
            'start' => $this->start,
            'count' => $this->count,
            'total' => $this->total
        ));
        $root->appendChild($items);

        /** @var XMLItem $item */
        foreach ($this->items as $item) {
            $itemDom = $item->getDomSubtree($document);
            $items->appendChild($itemDom);
        }

        return $document;
    }
}
