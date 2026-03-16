<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {
            // Add user_fingerprint column if it doesn't exist
            if (!Schema::hasColumn('tblreviews', 'user_fingerprint')) {
                $table->string('user_fingerprint')->nullable()->after('ip_address')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {
            $table->dropColumn('user_fingerprint');
        });
    }
};
