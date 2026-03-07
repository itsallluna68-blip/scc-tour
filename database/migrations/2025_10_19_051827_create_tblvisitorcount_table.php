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
        Schema::create('tblvisitorcount', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('date_add')->useCurrent();
            $table->integer('vmonth');
            $table->integer('vyear');
            $table->integer('total_visitors');
            $table->string('loc')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblvisitorcount');
    }
};
