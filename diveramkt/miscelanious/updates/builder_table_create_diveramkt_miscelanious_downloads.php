<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousDownloads extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_downloads', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description');
            $table->text('link');
            $table->string('file', 255);
            $table->integer('sort_order');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_downloads');
    }
}
