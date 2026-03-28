<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'deactivated'])
                  ->default('pending')
                  ->after('visitor_type'); // or after any column you want
        });
    }

    public function down(): void
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};