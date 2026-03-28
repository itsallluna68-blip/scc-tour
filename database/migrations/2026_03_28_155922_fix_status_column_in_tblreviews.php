<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Case 1: Column does NOT exist → create it
        if (!Schema::hasColumn('tblreviews', 'status')) {

            Schema::table('tblreviews', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'deactivated'])
                      ->default('pending')
                      ->after('ip_address');
            });

        } else {
            // Case 2: Column exists but WRONG TYPE (integer)
            DB::statement("
                ALTER TABLE tblreviews 
                MODIFY status ENUM('pending','approved','deactivated') 
                NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        // Optional rollback (back to integer)
        DB::statement("
            ALTER TABLE tblreviews 
            MODIFY status INT DEFAULT 0
        ");
    }
};