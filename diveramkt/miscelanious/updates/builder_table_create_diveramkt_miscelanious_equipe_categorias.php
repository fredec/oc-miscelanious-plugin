<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousEquipeCategorias extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_equipe_categorias', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('enabled')->default(1);
            $table->integer('sort_order')->default(0);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_equipe_categorias');
    }
}
