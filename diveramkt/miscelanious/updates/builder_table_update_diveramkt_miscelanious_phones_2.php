<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousPhones2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->text('numbers')->nullable();
            $table->integer('sort_order')->nullable(false)->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_phones', function($table)
        {
            $table->dropColumn('numbers');
            $table->integer('sort_order')->nullable()->default(NULL)->change();
        });
    }
}
