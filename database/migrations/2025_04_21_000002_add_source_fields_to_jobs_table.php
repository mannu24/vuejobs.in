<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('apply_url')->nullable()->after('description');
            $table->string('source')->default('manual')->after('apply_url');
            $table->string('source_url')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['apply_url', 'source', 'source_url']);
        });
    }
};
