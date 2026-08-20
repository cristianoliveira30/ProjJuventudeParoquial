<?php

namespace App\Class;

use App\Interfaces\DateRange as DateRangeInterface;
use Carbon\CarbonImmutable;
use Generator;

class DateRange implements DateRangeInterface
{
    public function __construct(
        public readonly CarbonImmutable $inicio,
        public readonly CarbonImmutable $fim
    ) {}

    /**
     * Converte o intervalo em um array de datas (somente data, sem hora),
     * no formato YYYY-MM-DD.
     *
     * @return array [YYYY-MM-DD, YYYY-MM-DD]
     */
    public function toDateArray(): array
    {
        return [
            $this->inicio->toDateString(),
            $this->fim->toDateString()
        ];
    }

    /**
     * Converte o intervalo em um array de timestamps (data + hora),
     * no formato YYYY-MM-DD H:i:s.
     *
     * @return array [YYYY-MM-DD H:i:s, YYYY-MM-DD H:i:s]
     */
    public function toTimestampArray(): array
    {
        return [
            $this->inicio->toDateTimeString(),
            $this->fim->toDateTimeString()
        ];
    }

    /**
     * Gera (yield) cada dia do intervalo, iniciando em "inicio" no começo do dia (00:00:00)
     * e avançando 1 dia por vez, até alcançar o "fim" (inclusive).
     *
     * @return Generator<CarbonImmutable>
     */
    public function days(): Generator
    {
        $current = $this->inicio->startOfDay();
        while ($current <= $this->fim) {
            yield $current;
            $current = $current->addDay();
        }
    }
}
