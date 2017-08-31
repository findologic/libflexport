<?php

namespace FINDOLOGIC\Export\XML\Commands;

function read_data($path)
{
    $rawData = file_get_contents($path);
    $parsedData = array();
    $id = 0;

    $lines = explode("\n", $rawData);

    foreach ($lines as $line) {
        $columns = explode(',', $line);
        $columns[0] = $id;
        $id++;

        array_push($parsedData, $columns);
    }

    return $parsedData;
}
