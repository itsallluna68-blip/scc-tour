<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            // add loc column if it doesn't exist; make sure it has a default so
            // existing inserts won't break.
            if (!Schema::hasColumn('tblvisitorcount', 'loc')) {
                $table->string('loc')->default('')->after('total_visitors');
            }
        });

        // if the column already exists but has no default, force a default via
        // a direct statement (requires no dbal). this handles the situation
        // where the table was created manually or with a different migration
        // earlier.
        if (Schema::hasColumn('tblvisitorcount', 'loc')) {
            \Illuminate\Support\Facades\DB::statement(
                "ALTER TABLE tblvisitorcount MODIFY loc VARCHAR(255) NOT NULL DEFAULT ''"
            );
        }

        Schema::table('tblvisitorcount', function (Blueprint $table) {
            // ensure the other columns expected by the application are present.
            if (!Schema::hasColumn('tblvisitorcount', 'vmonth')) {
                $table->integer('vmonth')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tblvisitorcount', 'vyear')) {
                $table->integer('vyear')->nullable()->after('vmonth');
            }
            if (!Schema::hasColumn('tblvisitorcount', 'total_visitors')) {
                $table->integer('total_visitors')->default(0)->after('vyear');
            }
            if (!Schema::hasColumn('tblvisitorcount', 'date_add')) {
                $table->date('date_add')->nullable()->after('loc');
            }
        });
    }

    public function down()
    {
        Schema::table('tblvisitorcount', function (Blueprint $table) {
            if (Schema::hasColumn('tblvisitorcount', 'loc')) {
                $table->dropColumn('loc');
            }
            // do not drop vmonth/vyear/total_visitors/etc since they are core to
            // the application's logic; a rollback shouldn't destructively
            // remove them automatically.
        });
    }
};
