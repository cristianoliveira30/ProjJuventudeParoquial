<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pastoral_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 40);
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->string('meeting_schedule')->nullable();
            $table->string('meeting_location')->nullable();
            $table->string('public_contact')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pastoral_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pastoral_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pastoral_group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pastoral_group_user');
        Schema::dropIfExists('pastoral_groups');
    }
};
