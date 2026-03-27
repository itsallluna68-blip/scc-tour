<?php
// database/migrations/2026_02_21_000001_create_tblvisitorcount_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tblvisitorcount', function (Blueprint $table) {
            $table->id();
            $table->date('date_visit')->unique();
            $table->integer('local_v')->default(0);
            $table->integer('national_v')->default(0);
            $table->integer('inter_v')->default(0);
            $table->integer('amt_visit')->default(0);
            $table->date('date_add');
            $table->date('date_mod')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tblvisitorcount');
    }
};