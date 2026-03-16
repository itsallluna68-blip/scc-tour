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
        Schema::create('tblevents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('events', 100);
            $table->text('e_info')->nullable();
            $table->date('e_datetime')->nullable();
            $table->string('e_location', 250)->nullable();
            $table->text('e_maplink')->nullable();
            $table->text('e_link')->nullable();
            $table->text('pics')->nullable();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblevents');
    }
};
