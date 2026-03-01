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
        // Rename reviews table to tblreviews
        Schema::rename('reviews', 'tblreviews');
        
        // Add missing columns
        Schema::table('tblreviews', function (Blueprint $table) {
            // Rename id to rid
            $table->renameColumn('id', 'rid');
            
            // Add missing columns
            if (!Schema::hasColumn('tblreviews', 'email')) {
                $table->string('email')->after('place_id');
            }
            
            if (!Schema::hasColumn('tblreviews', 'ratings')) {
                $table->tinyInteger('ratings')->change();
            }
            
            if (!Schema::hasColumn('tblreviews', 'feedback')) {
                $table->text('feedback')->change();
            }
            
            if (!Schema::hasColumn('tblreviews', 'date')) {
                $table->dateTime('date')->useCurrent()->after('feedback');
            }
            
            if (!Schema::hasColumn('tblreviews', 'rpic0')) {
                $table->string('rpic0')->nullable()->after('date');
                $table->string('rpic1')->nullable()->after('rpic0');
                $table->string('rpic2')->nullable()->after('rpic1');
            }
            
            // Remove created_at and updated_at if they exist
            if (Schema::hasColumn('tblreviews', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('tblreviews', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('tblreviews', 'reviews');
    }
};
