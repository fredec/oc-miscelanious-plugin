<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousDownloads3 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->integer('sort_order')->default(1)->change();
            $table->boolean('enabled')->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->integer('sort_order')->default(null)->change();
            $table->boolean('enabled')->default(1)->change();
        });
    }
}
