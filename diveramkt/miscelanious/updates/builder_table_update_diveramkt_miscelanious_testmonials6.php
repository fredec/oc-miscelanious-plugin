<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials6 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->text('infos')->nullable();
            $table->boolean('enabled')->nullable()->change();
            $table->integer('sort_order')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->dropColumn('infos');
            $table->boolean('enabled')->nullable(false)->change();
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
}