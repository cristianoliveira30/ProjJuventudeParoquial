<?php

use App\Services\LiturgiaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

it('keeps the liturgy view data complete when the provider is unavailable', function () {
    config(['cache.default' => 'array']);
    Cache::forget('liturgia_diaria_'.now()->format('Ymd'));
    Http::fake(['https://liturgia.up.railway.app/*' => Http::response([], 503)]);

    $liturgia = app(LiturgiaService::class)->getLiturgiaDoDia();

    expect($liturgia)->toHaveKeys([
        'data', 'dia', 'liturgia', 'oferendas', 'comunhao',
        'primeiraLeitura', 'salmo', 'segundaLeitura', 'evangelho', 'antifonas',
    ]);
});

it('uses the daily provider response when it is available', function () {
    config(['cache.default' => 'array']);
    Cache::forget('liturgia_diaria_'.now()->format('Ymd'));
    Http::fake(['https://liturgia.up.railway.app/*' => Http::response([
        'data' => '20 de agosto de 2026',
        'liturgia' => 'Tempo Comum',
        'primeiraLeitura' => 'Leitura de teste',
        'segundaLeitura' => null,
    ])]);

    $liturgia = app(LiturgiaService::class)->getLiturgiaDoDia();

    expect($liturgia['data'])->toBe('20 de agosto de 2026')
        ->and($liturgia['liturgia'])->toBe('Tempo Comum')
        ->and($liturgia['primeiraLeitura'])->toBe('Leitura de teste')
        ->and($liturgia['segundaLeitura'])->toBeString();
});
