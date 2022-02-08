<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousTestmonials4 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 255)->default(null)->change();
            $table->string('business', 255)->default(null)->change();
            $table->string('position', 255)->default(null)->change();
            $table->boolean('enabled')->nullable()->change();
            $table->integer('sort_order')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->string('name', 100)->default('NULL')->change();
            $table->string('business', 100)->default('NULL')->change();
            $table->string('position', 50)->default('NULL')->change();
            $table->boolean('enabled')->nullable(false)->change();
            $table->integer('sort_order')->nullable(false)->change();
        });
    }
}
