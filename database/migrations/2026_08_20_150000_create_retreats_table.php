<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retreats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->string('location')->nullable();
            $table->timestamp('starts_at')->index();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('registration_opens_at')->nullable();
            $table->timestamp('registration_closes_at')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->string('public_contact')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('retreat_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retreat_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('birth_date');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('community')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->text('internal_notes')->nullable();
            $table->timestamp('consented_at');
            $table->timestamps();
            $table->unique(['retreat_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retreat_registrations');
        Schema::dropIfExists('retreats');
    }
};
