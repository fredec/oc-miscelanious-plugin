<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousDownloads5 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->renameColumn('link', 'url_externa');
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->renameColumn('url_externa', 'link');
        });
    }
}
