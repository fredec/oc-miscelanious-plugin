<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousContentBlocks extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_content_blocks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('content')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->integer('enabled')->nullable()->default(1);
            $table->text('infos')->nullable();
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_content_blocks');
    }
}
