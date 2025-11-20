<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('status')->default('applied');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('hired_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['job_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};

