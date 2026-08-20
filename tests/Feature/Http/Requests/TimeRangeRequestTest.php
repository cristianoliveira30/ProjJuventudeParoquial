<?php

use App\Http\Requests\TimeRangeRequest;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/testing/time-range-request', function (TimeRangeRequest $request) {
        return response()->json($request->validated());
    });
});

it('defaults tempo to hoje when it is not provided', function () {
    $this->getJson('/testing/time-range-request')
        ->assertOk()
        ->assertExactJson([
            'tempo' => 'hoje',
        ]);
});

it('accepts every supported period value', function (string $tempo) {
    $this->getJson('/testing/time-range-request?tempo='.$tempo)
        ->assertOk()
        ->assertJsonPath('tempo', $tempo);
})->with([
    'hoje',
    'ontem',
    'semana-atual',
    'semana-passada',
    'mes-atual',
    'mes-passado',
]);

it('requires start and end dates when the period is custom', function () {
    $this->getJson('/testing/time-range-request?tempo=periodo')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['inicio', 'fim']);
});

it('accepts a valid custom period', function () {
    $this->getJson('/testing/time-range-request?tempo=periodo&inicio=2026-06-01&fim=2026-06-19')
        ->assertOk()
        ->assertExactJson([
            'tempo' => 'periodo',
            'inicio' => '2026-06-01',
            'fim' => '2026-06-19',
        ]);
});

it('rejects unsupported period values', function () {
    $this->getJson('/testing/time-range-request?tempo=ano-atual')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['tempo']);
});

it('rejects dates that are not formatted as year month day', function () {
    $this->getJson('/testing/time-range-request?tempo=periodo&inicio=01/06/2026&fim=19/06/2026')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['inicio', 'fim']);
});

it('rejects custom periods where the end date is before the start date', function () {
    $this->getJson('/testing/time-range-request?tempo=periodo&inicio=2026-06-19&fim=2026-06-01')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim']);
});
