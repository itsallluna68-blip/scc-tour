<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {

            // Add only if it doesn't exist (prevents Railway errors)
            if (!Schema::hasColumn('tblvisitorcount', 'visitor_type')) {
                $table->enum('visitor_type', ['resident', 'visitor'])
                      ->default('resident')
                      ->after('loc');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {

            // Drop only if it exists
            if (Schema::hasColumn('tblvisitorcount', 'visitor_type')) {
                $table->dropColumn('visitor_type');
            }
        });
    }
};