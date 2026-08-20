<?php

use App\Services\TimeRangeService;
use Carbon\CarbonImmutable;

afterEach(function () {
    CarbonImmutable::setTestNow();
});

it('resolves today using the application timezone', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'hoje']);

    expect($range->toTimestampArray())->toBe([
        '2026-06-19 00:00:00',
        '2026-06-19 23:59:59',
    ]);
});

it('resolves yesterday using the application timezone', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'ontem']);

    expect($range->toTimestampArray())->toBe([
        '2026-06-18 00:00:00',
        '2026-06-18 23:59:59',
    ]);
});

it('resolves the current week from monday to sunday', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'semana-atual']);

    expect($range->toTimestampArray())->toBe([
        '2026-06-15 00:00:00',
        '2026-06-21 23:59:59',
    ]);
});

it('resolves the previous week from monday to sunday', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'semana-passada']);

    expect($range->toTimestampArray())->toBe([
        '2026-06-08 00:00:00',
        '2026-06-14 23:59:59',
    ]);
});

it('resolves the current month', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'mes-atual']);

    expect($range->toTimestampArray())->toBe([
        '2026-06-01 00:00:00',
        '2026-06-30 23:59:59',
    ]);
});

it('resolves the previous month', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve(['tempo' => 'mes-passado']);

    expect($range->toTimestampArray())->toBe([
        '2026-05-01 00:00:00',
        '2026-05-31 23:59:59',
    ]);
});

it('resolves a custom period using full days', function () {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-06-19 14:30:00', 'America/Belem'));

    $range = (new TimeRangeService())->resolve([
        'tempo' => 'periodo',
        'inicio' => '2026-04-10',
        'fim' => '2026-04-12',
    ]);

    expect($range->toTimestampArray())->toBe([
        '2026-04-10 00:00:00',
        '2026-04-12 23:59:59',
    ]);
});
