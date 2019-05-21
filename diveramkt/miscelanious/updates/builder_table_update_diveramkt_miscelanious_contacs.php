<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousContacs extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_contacs', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
            $table->text('description')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_contacs', function($table)
        {
            $table->increments('id')->unsigned()->change();
            $table->text('description')->nullable(false)->change();
        });
    }
}