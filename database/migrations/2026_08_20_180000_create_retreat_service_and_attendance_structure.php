<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retreats', function (Blueprint $table) {
            $table->string('servant_registration_token', 64)->nullable()->unique()->after('public_contact');
            $table->boolean('servant_registration_enabled')->default(false)->after('servant_registration_token');
            $table->timestamp('servant_registration_opens_at')->nullable()->after('servant_registration_enabled');
            $table->timestamp('servant_registration_closes_at')->nullable()->after('servant_registration_opens_at');
        });

        Schema::create('retreat_ministries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retreat_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140);
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['retreat_id', 'slug']);
        });

        Schema::create('retreat_servants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retreat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('preferred_ministry_id')->nullable()->constrained('retreat_ministries')->nullOnDelete();
            $table->foreignId('assigned_ministry_id')->nullable()->constrained('retreat_ministries')->nullOnDelete();
            $table->string('name', 180);
            $table->date('birth_date');
            $table->string('email', 180);
            $table->string('phone', 40);
            $table->string('community', 180)->nullable();
            $table->string('mother_name', 180)->nullable();
            $table->string('father_name', 180)->nullable();
            $table->string('guardian_name', 180)->nullable();
            $table->string('guardian_phone', 40)->nullable();
            $table->string('emergency_contact_name', 180);
            $table->string('emergency_contact_phone', 40);
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->text('dietary_restrictions')->nullable();
            $table->unsignedSmallInteger('previous_retreat_year');
            $table->string('status', 20)->default('pending')->index();
            $table->text('internal_notes')->nullable();
            $table->timestamp('consented_at');
            $table->timestamp('health_data_consented_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['retreat_id', 'email']);
        });

        Schema::create('retreat_attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retreat_id')->constrained()->cascadeOnDelete();
            $table->string('audience', 20);
            $table->string('direction', 20);
            $table->string('status', 20)->default('not_started')->index();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['retreat_id', 'audience', 'direction'], 'retreat_attendance_session_unique');
        });

        Schema::create('retreat_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retreat_attendance_session_id')->constrained()->cascadeOnDelete();
            $table->morphs('attendee');
            $table->timestamp('present_at');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(
                ['retreat_attendance_session_id', 'attendee_type', 'attendee_id'],
                'retreat_attendance_record_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retreat_attendance_records');
        Schema::dropIfExists('retreat_attendance_sessions');
        Schema::dropIfExists('retreat_servants');
        Schema::dropIfExists('retreat_ministries');

        Schema::table('retreats', function (Blueprint $table) {
            $table->dropUnique(['servant_registration_token']);
            $table->dropColumn([
                'servant_registration_token', 'servant_registration_enabled',
                'servant_registration_opens_at', 'servant_registration_closes_at',
            ]);
        });
    }
};
