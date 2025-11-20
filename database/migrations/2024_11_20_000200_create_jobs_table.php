<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department')->nullable();
            $table->string('location_type')->default('remote');
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();
            $table->string('contract_type')->default('full_time');
            $table->string('experience_level')->nullable();
            $table->string('vue_version')->nullable();
            $table->string('nuxt_version')->nullable();
            $table->boolean('requires_typescript')->default(false);
            $table->integer('salary_min')->nullable();
            $table->integer('salary_max')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('salary_interval')->default('yearly');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('featured_until')->nullable();
            $table->json('skills')->nullable();
            $table->json('benefits')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_saves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['job_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_saves');
        Schema::dropIfExists('jobs');
    }
};

