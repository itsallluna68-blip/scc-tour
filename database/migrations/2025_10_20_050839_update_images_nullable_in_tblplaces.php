<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tblplaces', function (Blueprint $table) {
            // Make optional fields nullable
            $table->string('contact')->nullable()->change();
            $table->text('history')->nullable()->change();
            $table->string('link1')->nullable()->change();
            $table->string('link2')->nullable()->change();
            $table->string('email')->nullable()->change();



            // Default status = 1
            $table->boolean('status')->default(1)->change();
        });
    }

    public function down()
    {
        Schema::table('tblplaces', function (Blueprint $table) {
            $table->string('contact')->change();
            $table->text('history')->change();
            $table->string('link1')->change();
            $table->string('link2')->change();
            $table->string('email')->change();
            $table->boolean('status')->change();
        });
    }
};
