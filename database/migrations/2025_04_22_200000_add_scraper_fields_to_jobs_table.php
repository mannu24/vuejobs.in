<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('external_id')->nullable()->index()->after('id');
            $table->string('hash')->nullable()->unique()->after('external_id');
            $table->boolean('is_verified')->default(false)->after('source_url');
            $table->string('company_name')->nullable()->after('source_url');
        });

        // Make company_id and creator_id nullable for scraped jobs
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->change();
            $table->foreignId('creator_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['external_id']);
            $table->dropUnique(['hash']);
            $table->dropColumn(['external_id', 'hash', 'is_verified', 'company_name']);
        });
    }
};
