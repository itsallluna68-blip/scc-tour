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
        Schema::table('tblplaces', function (Blueprint $table) {
            // Change 'images' from text to MEDIUMBLOB
            $table->mediumBlob('images')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblplaces', function (Blueprint $table) {
            // Revert back to text if rolled back
            $table->text('images')->nullable()->change();
        });
    }
};
