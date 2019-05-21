<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousContacs extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_contacs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type', 255);
            $table->string('value', 255);
            $table->text('description');
            $table->string('icon', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_contacs');
    }
}