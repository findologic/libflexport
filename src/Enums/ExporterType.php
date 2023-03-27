<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Enums;

enum ExporterType: string
{
    /**
     * XML-based export format.
     *
     * @see https://docs.findologic.com/doku.php?id=xml_export_documentation:XML_2_format
     */
    case XML = 'XML';

    /**
     * CSV-based export format. Does not support usergroups.
     *
     * @see https://docs.findologic.com/doku.php?id=csv_export_documentation:csv_2_format
     */
    case CSV = 'CSV';
}
