<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousCompanies3 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->text('phones')->nullable();
            $table->text('mobiles')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->dropColumn('phones');
            $table->dropColumn('mobiles');
        });
    }
}
