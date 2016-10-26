<?php

namespace FINDOLOGIC\XmlExport\Helpers;


/**
 * Interface Serializable
 * @package FINDOLOGIC\XmlExport
 */
abstract class Serializable
{
    /**
     * @param \DOMDocument The document to work with. It should be passed along if getDomDocument of other objects is
     *      called. It should not be modified, unless at the root!
     * @return \DOMElement The root element of whatever was generated.
     */
    public abstract function getDomSubtree(\DOMDocument $document);
}