<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousEquipe extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_equipe', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('sort_order')->default(0);
            $table->integer('enabled')->default(1);
            $table->integer('equipecategorias_id')->default(0);
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('position', 255)->nullable();
            $table->text('links')->nullable();
            $table->text('email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_equipe');
    }
}
