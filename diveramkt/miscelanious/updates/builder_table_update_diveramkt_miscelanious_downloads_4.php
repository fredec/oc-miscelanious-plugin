<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousDownloads4 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->string('cover_img', 255);
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_downloads', function($table)
        {
            $table->dropColumn('cover_img');
        });
    }
}
