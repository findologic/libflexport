<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

/**
 * Thrown in case an XML export page does not conform to the schema.
 */
class XMLSchemaViolationException extends RuntimeException
{
    public function __construct(array $validationErrors)
    {
        parent::__construct('XML schema validation failed: ' . implode(';', $validationErrors));
    }
}
