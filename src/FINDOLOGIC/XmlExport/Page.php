<?php

namespace FINDOLOGIC\XmlExport;


use FINDOLOGIC\XmlExport\Elements\Item;
use FINDOLOGIC\XmlExport\Helpers\XmlHelper;

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

    public function addItem(Item $item)
    {
        array_push($this->items, $item);
    }

    public function getXml()
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        $root = XmlHelper::createElement($document, 'findologic', array('version' => '1.0'));
        $document->appendCHild($root);

        $items = XmlHelper::createElement($document, 'items', array(
            'start' => $this->start,
            'count' => $this->count,
            'total' => $this->total
        ));
        $root->appendChild($items);

        /** @var Item $item */
        foreach ($this->items as $item) {
            $itemDom = $item->getDomSubtree($document);
            $items->appendChild($itemDom);
        }

        return $document;
    }
}