<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE tbluserhistory
            MODIFY id INT(11) NOT NULL AUTO_INCREMENT,
            ADD PRIMARY KEY (id)
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE tbluserhistory
            MODIFY id INT(11) NOT NULL,
            DROP PRIMARY KEY
        ");
    }
};