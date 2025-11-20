<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('size')->nullable();
            $table->string('industry')->nullable();
            $table->text('about')->nullable();
            $table->json('hiring_regions')->nullable();
            $table->json('tech_stack')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamp('highlighted_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

