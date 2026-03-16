<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert old values first
        DB::table('tblusers')->where('status', 1)->update(['status' => 'active']);
        DB::table('tblusers')->where('status', 0)->update(['status' => 'inactive']);

        // Change column type
        DB::statement("ALTER TABLE tblusers MODIFY status ENUM('active','inactive') DEFAULT 'active'");
    }

    public function down(): void
    {
        // revert enum back to integer
        DB::statement("ALTER TABLE tblusers MODIFY status TINYINT(1) DEFAULT 1");

        DB::table('tblusers')->where('status', 'active')->update(['status' => 1]);
        DB::table('tblusers')->where('status', 'inactive')->update(['status' => 0]);
    }
};