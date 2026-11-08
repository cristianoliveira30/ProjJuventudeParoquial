<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const COLUMNS = ['allergies', 'medical_conditions', 'medications', 'dietary_restrictions'];

    public function up(): void
    {
        foreach (['retreat_registrations', 'retreat_servants'] as $table) {
            DB::table($table)->orderBy('id')->each(function ($row) use ($table) {
                $update = [];
                foreach (self::COLUMNS as $column) {
                    $value = $row->$column;
                    if ($value === null || $value === '') {
                        continue;
                    }
                    try {
                        Crypt::decryptString($value); // já criptografado: pula
                    } catch (DecryptException) {
                        $update[$column] = Crypt::encryptString($value);
                    }
                }
                if ($update) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['retreat_registrations', 'retreat_servants'] as $table) {
            DB::table($table)->orderBy('id')->each(function ($row) use ($table) {
                $update = [];
                foreach (self::COLUMNS as $column) {
                    if ($row->$column === null || $row->$column === '') {
                        continue;
                    }
                    try {
                        $update[$column] = Crypt::decryptString($row->$column);
                    } catch (DecryptException) {
                    }
                }
                if ($update) {
                    DB::table($table)->where('id', $row->id)->update($update);
                }
            });
        }
    }
};
