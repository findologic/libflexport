<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Helpers;

use DOMDocument;
use DOMElement;

final class XMLHelper
{
    /**
     * Shortcut for creating an XML element.
     *
     * @param DOMDocument $document The document used to create the element. It will NOT be modified.
     * @param string $name Name of the element.
     * @param array $attributes String-to-string mapping of attributes to set on the element.
     * @return DOMElement The newly constructed independent DOM element.
     */
    public static function createElement(DOMDocument $document, string $name, array $attributes = []): DOMElement
    {
        $element = $document->createElement($name);

        foreach ($attributes as $attribName => $attribValue) {
            $element->setAttribute($attribName, (string) $attribValue);
        }

        return $element;
    }

    /**
     * Shortcut for creating an XML element that contains CDATA-wrapped text.
     *
     * @param DOMDocument $document The document used to create the element. It will NOT be modified.
     * @param string $name Name of the element.
     * @param string $text The text body of the document.
     * @param array $attributes String-to-string mapping of attributes to set on the element.
     * @return DOMElement The newly constructed independent DOM element.
     */
    public static function createElementWithText(
        DOMDocument $document,
        string $name,
        string $text,
        array $attributes = []
    ): DOMElement {
        $element = self::createElement($document, $name, $attributes);
        $wrappedText = $document->createCDATASection($text);
        $element->appendChild($wrappedText);

        return $element;
    }
}
