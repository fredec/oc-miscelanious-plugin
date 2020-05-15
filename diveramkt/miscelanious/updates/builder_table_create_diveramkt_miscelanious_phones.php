<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousPhones extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_phones', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('area_code', 10)->nullable();
            $table->string('number', 20);
            $table->string('icon', 20)->nullable()->default('phone');
            $table->string('info', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_phones');
    }
}
