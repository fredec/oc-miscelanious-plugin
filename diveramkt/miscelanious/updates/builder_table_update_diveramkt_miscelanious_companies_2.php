<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousCompanies2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->string('email', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->dropColumn('email');
        });
    }
}
