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
        Schema::create('tbluserhistory', function (Blueprint $table) {
            $table->integer('id');
            $table->string('user_type', 10);
            $table->string('username', 20);
            $table->string('full_name');
            $table->dateTime('date_time')->useCurrent();
            $table->text('action_taken');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbluserhistory');
    }
};
