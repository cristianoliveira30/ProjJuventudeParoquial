<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $defaults = [['Artes', 'artes'], ['Música', 'musica'], ['Cozinha', 'cozinha']];

        DB::table('retreats')->orderBy('id')->each(function ($retreat) use ($defaults, $now) {
            foreach ($defaults as $position => [$name, $slug]) {
                DB::table('retreat_ministries')->updateOrInsert(
                    ['retreat_id' => $retreat->id, 'slug' => $slug],
                    ['name' => $name, 'sort_order' => $position + 1, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                );
            }
        });
    }

    public function down(): void
    {
        // Preserva ministérios que possam ter recebido vínculos operacionais.
    }
};
