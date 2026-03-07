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
        Schema::create('tblreviews', function (Blueprint $table) {
            $table->increments('rid');
            $table->unsignedInteger('place_id');
            $table->string('email')->nullable();
            $table->string('name');
            $table->tinyInteger('ratings');
            $table->string('feedback', 550);
            $table->string('rpic0')->nullable();
            $table->string('rpic1')->nullable();
            $table->string('rpic2')->nullable();
            $table->date('date')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_fingerprint')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblreviews');
    }
};
