<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousSocial extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_social', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 20);
            $table->string('link', 255);
            $table->string('description', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_social');
    }
}
