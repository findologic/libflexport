<?php

namespace FINDOLOGIC\Tests;

use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use PHPUnit\Framework\TestCase;

class DataHelperTest extends TestCase
{
    /**
     * @dataProvider emptyValueProvider
     *
     * @param $value
     * @param $shouldCauseException
     */
    public function testEmptyValueDetectsEmptyStringsOnly($value, $shouldCauseException)
    {
        try {
            DataHelper::checkForEmptyValue($value);

            if ($shouldCauseException) {
                $this->fail('Should be detected as empty value.');
            }
        } catch (EmptyValueNotAllowedException $e) {
            if (!$shouldCauseException) {
                $this->fail('Should not be detected as empty value.');
            }
        }
    }

    /**
     * Scenarios for empty value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function emptyValueProvider()
    {
        return [
            'empty string' => ['', true],
            'non-zero integer' => [123, false],
            'zero as integer' => [0, false],
            'non-zero float' => [12.3, false],
            'zero as float' => [0.0, false],
            'zero as string' => ['0', false],
            'null' => ['', true],
            'false' => [false, true]
        ];
    }
}
