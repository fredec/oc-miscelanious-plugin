<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials5 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 255)->change();
            $table->string('business', 255)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 100)->change();
            $table->string('business', 100)->change();
        });
    }
}