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
        Schema::create('tblactivities', function (Blueprint $table) {
            $table->integer('aid', true);
            $table->string('a_name', 200);
            $table->text('a_info')->nullable();
            $table->text('img0');
            $table->tinyInteger('a_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblactivities');
    }
};
