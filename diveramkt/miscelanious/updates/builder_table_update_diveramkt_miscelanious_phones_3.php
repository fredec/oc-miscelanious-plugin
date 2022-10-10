<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousPhones3 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->string('number', 20)->nullable()->change();
            $table->integer('sort_order')->nullable()->default(1)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->string('number', 20)->nullable(false)->change();
            $table->integer('sort_order')->nullable(false)->default(0)->change();
        });
    }
}
