<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('place_id');
        $table->string('name');
        $table->string('subject');
        $table->text('review');
        $table->tinyInteger('rating'); // 1–5 stars
        $table->timestamps();

        $table->foreign('place_id')
              ->references('id')
              ->on('tblplaces')
              ->onDelete('cascade');
    });
    // Schema::create('tblreviews', function (Blueprint $table) {
    //     $table->id('rid');

    //     // FOREIGN KEY → tblplaces.id
    //     $table->unsignedBigInteger('place_id');
    //     $table->foreign('place_id')->references('id')->on('tblplaces')->onDelete('cascade');

    //     // REVIEW FIELDS
    //     $table->string('email');                    // will validate using @
    //     $table->string('name');
    //     $table->tinyInteger('ratings');             // 1 to 5 stars
    //     $table->string('subject', 100);
    //     $table->string('feedback', 550);            // 550 characters max
    //     $table->date('date')->default(DB::raw('CURRENT_DATE'));

    //     $table->timestamps();
    // });
}

};
