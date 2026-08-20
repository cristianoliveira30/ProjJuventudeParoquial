<?php

namespace App\Interfaces;

use Carbon\CarbonImmutable;
use Generator;

interface DateRange
{
    public function __construct(CarbonImmutable $inicio, CarbonImmutable $fim);
    public function toDateArray(): array;
    public function toTimestampArray(): array;
    public function days(): Generator;
}
