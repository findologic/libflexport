<?php


namespace FINDOLOGIC\Export\Helpers;

/**
 * Value that has a method to access a readable name for the value.
 */
interface NameAwareValue
{
    /**
     * @return string The name of the value , e.g. name or description, which is used for more accurate error
     *      reporting.
     */
    public function getValueName(): string;
}
