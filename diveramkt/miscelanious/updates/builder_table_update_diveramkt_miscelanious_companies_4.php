<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousCompanies4 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->integer('main')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->dropColumn('main');
        });
    }
}
