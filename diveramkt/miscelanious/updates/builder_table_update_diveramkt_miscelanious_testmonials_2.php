<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->integer('sort_order')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
}
