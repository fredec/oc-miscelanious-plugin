<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousContentBlocks extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_content_blocks', function($table)
        {
            $table->integer('type')->nullable()->default(0);
            $table->text('content_code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_content_blocks', function($table)
        {
            $table->dropColumn('type');
            $table->dropColumn('content_code');
        });
    }
}
