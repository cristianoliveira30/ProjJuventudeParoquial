<?php

use App\Models\RetreatRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('encrypts old plaintext health data once and keeps it readable', function (string $table, string $model) {
    $retreatId = DB::table('retreats')->insertGetId(array_merge(
        collect(Schema::getColumns('retreats'))->reject(fn ($c) => $c['nullable'] || $c['auto_increment'] || $c['default'] !== null)
            ->mapWithKeys(fn ($c) => [$c['name'] => match (true) {
                str_contains($c['type'], 'int') => 1,
                str_contains($c['type'], 'datetime'), str_contains($c['type'], 'timestamp') => now(),
                str_contains($c['type'], 'date') => now()->toDateString(),
                default => 'x'.uniqid(),
            }])->all(),
    ));
    $columns = collect(Schema::getColumns($table))->reject(fn ($c) => $c['nullable'] || $c['auto_increment'] || $c['default'] !== null);
    $row = $columns->mapWithKeys(fn ($c) => [$c['name'] => match (true) {
        $c['name'] === 'retreat_id' => $retreatId,
        str_contains($c['type'], 'int') => 1,
        str_contains($c['type'], 'datetime'), str_contains($c['type'], 'timestamp') => now(),
        str_contains($c['type'], 'date') => now()->toDateString(),
        default => 'x'.uniqid(),
    }])->all();
    $id = DB::table($table)->insertGetId($row + ['allergies' => 'amendoim']);

    $migration = require database_path('migrations/2026_11_07_000001_encrypt_retreat_health_data.php');
    $migration->up();
    $migration->up(); // rodar de novo não pode criptografar duas vezes

    expect(DB::table($table)->where('id', $id)->value('allergies'))->not->toBe('amendoim')
        ->and($model::find($id)->allergies)->toBe('amendoim');
})->with([
    ['retreat_registrations', RetreatRegistration::class],
    ['retreat_servants', \App\Models\RetreatServant::class],
]);
