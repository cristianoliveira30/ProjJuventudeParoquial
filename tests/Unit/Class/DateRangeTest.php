<?php

use App\Class\DateRange;
use Carbon\CarbonImmutable;

it('returns the start and end dates as date strings', function () {
    $range = new DateRange(
        CarbonImmutable::parse('2026-06-01 08:30:00', 'America/Belem'),
        CarbonImmutable::parse('2026-06-03 18:45:00', 'America/Belem'),
    );

    expect($range->toDateArray())->toBe([
        '2026-06-01',
        '2026-06-03',
    ]);
});

it('returns the start and end dates as timestamps', function () {
    $range = new DateRange(
        CarbonImmutable::parse('2026-06-01 08:30:00', 'America/Belem'),
        CarbonImmutable::parse('2026-06-03 18:45:00', 'America/Belem'),
    );

    expect($range->toTimestampArray())->toBe([
        '2026-06-01 08:30:00',
        '2026-06-03 18:45:00',
    ]);
});

it('yields every day in the range including the final day', function () {
    $range = new DateRange(
        CarbonImmutable::parse('2026-06-01 08:30:00', 'America/Belem'),
        CarbonImmutable::parse('2026-06-03 18:45:00', 'America/Belem'),
    );

    $days = array_map(
        fn (CarbonImmutable $day) => $day->toDateTimeString(),
        iterator_to_array($range->days()),
    );

    expect($days)->toBe([
        '2026-06-01 00:00:00',
        '2026-06-02 00:00:00',
        '2026-06-03 00:00:00',
    ]);
});
