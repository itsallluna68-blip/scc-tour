<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {

            // Drop email ONLY if it exists
            if (Schema::hasColumn('tblreviews', 'email')) {
                $table->dropColumn('email');
            }

            // Add status ONLY if it does NOT exist
            if (!Schema::hasColumn('tblreviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'deactivated'])
                      ->default('pending')
                      ->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {

            // Re-add email only if missing
            if (!Schema::hasColumn('tblreviews', 'email')) {
                $table->string('email')->nullable();
            }

            // Drop status only if exists
            if (Schema::hasColumn('tblreviews', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};