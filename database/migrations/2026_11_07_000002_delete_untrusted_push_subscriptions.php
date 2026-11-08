<?php

use App\Http\Controllers\WebPushController;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('push_subscriptions')->orderBy('id')->each(function ($row) {
            if (! preg_match(WebPushController::ENDPOINT_PATTERN, $row->endpoint)) {
                DB::table('push_subscriptions')->where('id', $row->id)->delete();
            }
        });
    }

    public function down(): void {}
};
