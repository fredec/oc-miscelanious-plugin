<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousContacs2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_contacs', function($table)
        {
            $table->string('link', 255);
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_contacs', function($table)
        {
            $table->dropColumn('link');
        });
    }
}