<?php

namespace FINDOLOGIC\Export\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
  public static function assertMatchesRegularExpression(string $pattern, string $string, string $message = ''): void
  {
      if (method_exists(BaseTestCase::class, 'assertMatchesRegularExpression')) {
          parent::assertMatchesRegularExpression($pattern, $string, $message);
      } else {
          parent::assertRegExp($pattern, $string, $message);
      }
  }
}
