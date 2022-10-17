<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials7 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->text('video')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->dropColumn('video');
        });
    }
}
