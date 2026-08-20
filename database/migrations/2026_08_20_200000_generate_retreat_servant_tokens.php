<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('retreats')->whereNull('servant_registration_token')->orderBy('id')->each(function ($retreat) {
            DB::table('retreats')->where('id', $retreat->id)->update([
                'servant_registration_token' => bin2hex(random_bytes(32)),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void {}
};
