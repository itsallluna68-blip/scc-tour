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
        Schema::create('tblplaces', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200);
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->text('history')->nullable();
            $table->string('link1')->nullable();
            $table->string('link2')->nullable();
            $table->string('address', 200)->nullable();
            $table->string('email')->nullable();
            $table->boolean('status')->default(true);
            $table->text('transport')->nullable();
            $table->string('map_link', 200)->nullable();
            $table->text('opening_hours')->nullable();
            $table->boolean('is_popular')->default(false);
            // MEDIUMBLOB for images
            $table->mediumBlob('images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblplaces');
    }
};