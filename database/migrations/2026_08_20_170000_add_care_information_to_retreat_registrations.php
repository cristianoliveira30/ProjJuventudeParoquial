<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retreat_registrations', function (Blueprint $table) {
            $table->string('mother_name')->nullable()->after('community');
            $table->string('father_name')->nullable()->after('mother_name');
            $table->string('guardian_name')->nullable()->after('father_name');
            $table->string('guardian_phone', 40)->nullable()->after('guardian_name');
            $table->string('emergency_contact_name')->nullable()->after('guardian_phone');
            $table->string('emergency_contact_phone', 40)->nullable()->after('emergency_contact_name');
            $table->text('allergies')->nullable()->after('emergency_contact');
            $table->text('medical_conditions')->nullable()->after('allergies');
            $table->text('medications')->nullable()->after('medical_conditions');
            $table->text('dietary_restrictions')->nullable()->after('medications');
            $table->timestamp('health_data_consented_at')->nullable()->after('consented_at');
        });
    }

    public function down(): void
    {
        Schema::table('retreat_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'mother_name', 'father_name', 'guardian_name', 'guardian_phone',
                'emergency_contact_name', 'emergency_contact_phone', 'allergies',
                'medical_conditions', 'medications', 'dietary_restrictions',
                'health_data_consented_at',
            ]);
        });
    }
};
