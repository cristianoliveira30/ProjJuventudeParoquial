<?php

namespace App\Services;

use App\Class\DateRange;
use App\Enums\PeriodKey;
use Carbon\CarbonImmutable;

class TimeRangeService
{
    private string $timeZone = 'America/Belem';

    public function resolve(array $data): DateRange
    {
        $now = CarbonImmutable::now($this->timeZone);
        $periodo = PeriodKey::from($data['tempo']);

        [$inicio, $fim] = match ($periodo) {
            PeriodKey::HOJE           => [$now->startOfDay(), $now->endOfDay()],
            PeriodKey::ONTEM          => [$now->subDay()->startOfDay(), $now->subDay()->endOfDay()],
            PeriodKey::SEMANA_ATUAL   => [$now->startOfWeek(), $now->endOfWeek()],
            PeriodKey::SEMANA_PASSADA => [$now->subWeek()->startOfWeek(), $now->subWeek()->endOfWeek()],
            PeriodKey::MES_ATUAL      => [$now->startOfMonth(), $now->endOfMonth()],
            PeriodKey::MES_PASSADO    => [$now->subMonth()->startOfMonth(), $now->subMonth()->endOfMonth()],
            PeriodKey::PERIODO        => [
                CarbonImmutable::parse($data['inicio'], $this->timeZone)->startOfDay(),
                CarbonImmutable::parse($data['fim'], $this->timeZone)->endOfDay()
            ],
        };

        return new DateRange($inicio, $fim);
    }
}
