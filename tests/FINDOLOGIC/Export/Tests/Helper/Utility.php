<?php

namespace FINDOLOGIC\Export\Tests\Helper;


/**
 * Class Utility
 * @package FINDOLOGIC\Export\Tests\Helper
 */
class Utility
{
    /**
     * Generate a multi byte character string.
     *
     * @param int $stringLength The string length to generate.
     * @return string The multi byte character string.
     */
    public static function generateMultiByteCharacterString($stringLength)
    {
        return implode('', array_fill(0, $stringLength, '©'));
    }
}