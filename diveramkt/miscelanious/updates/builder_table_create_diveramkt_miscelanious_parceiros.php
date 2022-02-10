<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousParceiros extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_parceiros', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('enabled')->default(1);
            $table->integer('sort_order')->default(1);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_parceiros');
    }
}
