<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {
            // Remove email column
            $table->dropColumn('email');

            // Add status column
            $table->enum('status', ['pending', 'approved', 'deactivated'])
                  ->default('pending')
                  ->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('tblreviews', function (Blueprint $table) {
            // Re-add email column (rollback)
            $table->string('email')->nullable();

            // Remove status column
            $table->dropColumn('status');
        });
    }
};