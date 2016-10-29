<?php

namespace FINDOLOGIC\Export\Helpers;


class XMLHelper
{
    /**
     * Shortcut for creating an XML element.
     *
     * @param \DOMDocument $document The document used to create the element. It will NOT be modified.
     * @param string $name Name of the element.
     * @param array $attributes String-to-string mapping of attributes to set on the element.
     * @return \DOMElement The newly constructed independent DOM element.
     */
    public static function createElement(\DOMDocument $document, $name, array $attributes = array())
    {
        $element = $document->createElement($name);

        foreach ($attributes as $attribName => $attribValue) {
            $element->setAttribute($attribName, $attribValue);
        }

        return $element;
    }

    /**
     * Shortcut for creating an XML element that contains CDATA-wrapped text.
     *
     * @param \DOMDocument $document The document used to create the element. It will NOT be modified.
     * @param string $name Name of the element.
     * @param string $text The text body of the document.
     * @param array $attributes String-to-string mapping of attributes to set on the element.
     * @return \DOMElement The newly constructed independent DOM element.
     */
    public static function createElementWithText(\DOMDocument $document, $name, $text, array $attributes = array())
    {
        $element = self::createElement($document, $name, $attributes);
        $wrappedText = $document->createCDATASection($text);
        $element->appendChild($wrappedText);

        return $element;
    }
}