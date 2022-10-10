<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousDownloads extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->boolean('enabled');
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->dropColumn('enabled');
        });
    }
}
