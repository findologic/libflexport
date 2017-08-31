<?php

namespace FINDOLOGIC\Export\Helpers;

/**
 * Data that is serializable as CSV or XML.
 */
interface Serializable
{
    /**
     * @param \DOMDocument The document to work with. It should be passed along if getDomDocument of other objects is
     *      called. It should not be modified, unless at the root!
     * @return \DOMElement The root element of whatever was generated.
     */
    public function getDomSubtree(\DOMDocument $document);

    /**
     * @return string A CSV fragment that, combined with other fragments, will finally become an export file.
     */
    public function getCsvFragment();
}
