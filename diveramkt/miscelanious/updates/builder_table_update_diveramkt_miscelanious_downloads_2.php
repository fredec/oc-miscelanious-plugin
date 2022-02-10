<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousDownloads2 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->boolean('enabled')->default(1)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->boolean('enabled')->default(null)->change();
        });
    }
}
