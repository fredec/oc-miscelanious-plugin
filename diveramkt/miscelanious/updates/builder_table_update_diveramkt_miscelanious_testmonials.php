<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->boolean('enabled');
            $table->integer('sort_order');
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->dropColumn('enabled');
            $table->dropColumn('sort_order');
        });
    }
}