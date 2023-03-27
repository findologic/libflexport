<?php

namespace FINDOLOGIC\Export\Examples\Data;

use FINDOLOGIC\Export\Enums\ImageType;

class ExampleBaseItem
{
    /**
     * @var string
     */
    public const DEFAULT_USER_GROUP = '';

    /**
     * @var string
     */
    public const SPECIFIC_USER_GROUP = 'B2B';

    public string $id;

    /** @var array<string, string[]> */
    public array $orderNumbers = [];

    /** @var array<string, string> */
    public array $names = [];

    /** @var array<string, string> */
    public array $summaries = [];

    /**  @var array<string, string> */
    public array $descriptions = [];

    /** @var array<string, int|float> */
    public array $prices = [];

    /** @var array<string, int|float> */
    public array $overriddenPrices = [];

    /** @var array<string, string> */
    public array $urls = [];

    /** @var array<string, string[]> */
    public array $keywords = [];

    /** @var array<string, int> */
    public array $bonuses = [];

    /** @var array<string, int> */
    public array $salesFrequencies = [];

    /** @var array<string, string> */
    public array $dateAddeds = [];

    /** @var array<string, int> */
    public array $sorts = [];

    /** @var string[] */
    public array $groups = [];

    /** @var array<string, array<string, ImageType>> */
    public array $images = [];

    /** @var array<string, string[]> */
    public array $attributes = [];

    /** @var array<string, array<string, mixed>> */
    public array $properties = [];

    /** @var array<string, array<string, mixed>> */
    public array $variants = [];

    /** @var array<string, mixed> */
    public array $visibilities = [];
}
