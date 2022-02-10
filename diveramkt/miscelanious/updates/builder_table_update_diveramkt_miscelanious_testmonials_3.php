<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials3 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 100)->nullable()->change();
            $table->text('testmonial')->nullable()->change();
            $table->boolean('enabled')->default(1)->change();
            $table->integer('sort_order')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 100)->nullable(false)->change();
            $table->text('testmonial')->nullable(false)->change();
            $table->boolean('enabled')->default(null)->change();
            $table->integer('sort_order')->default(null)->change();
        });
    }
}
