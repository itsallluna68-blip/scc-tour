<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            // Change 'status' from integer to enum
            $table->enum('status', ['pending', 'approved', 'deactivated'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            // Revert back to integer if you ever rollback
            $table->integer('status')->default(0)->change();
        });
    }
};