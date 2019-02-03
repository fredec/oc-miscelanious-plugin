<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousTestmonials extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_testmonials', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 100);
            $table->string('business', 100)->nullable();
            $table->string('position', 50)->nullable();
            $table->text('testmonial');
            $table->text('image')->nullable();
            $table->text('link')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_testmonials');
    }
}
