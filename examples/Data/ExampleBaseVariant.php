<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Examples\Data;

final class ExampleBaseVariant
{
    public string $id;

    /** @var array<string, string[]> */
    public array $orderNumbers = [];

    /** @var array<string, string> */
    public array $names = [];

    /** @var array<string, int|float> */
    public array $prices = [];

    /** @var array<string, int|float> */
    public array $overriddenPrice = [];

    /** @var string[] */
    public array $groups = [];

    /** @var array<string, string[]> */
    public array $attributes = [];

    /** @var array<string, array<string, mixed>> */
    public array $properties = [];
}
