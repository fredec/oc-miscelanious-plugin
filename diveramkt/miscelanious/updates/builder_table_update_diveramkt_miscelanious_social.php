<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousSocial extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_social', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
        
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
        
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_social', function($table)
        {
            $table->dropColumn('sort_order');
        });
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->dropColumn('sort_order');
        });
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}