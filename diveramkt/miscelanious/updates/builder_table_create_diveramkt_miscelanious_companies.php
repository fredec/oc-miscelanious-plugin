<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDiveramktMiscelaniousCompanies extends Migration
{
    public function up()
    {
        Schema::create('diveramkt_miscelanious_companies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 100)->nullable();
            $table->string('social', 100)->nullable();
            $table->string('cnpj', 20)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('addon', 50)->nullable();
            $table->string('neighborhood', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('maps_link', 255)->nullable();
            $table->string('area_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('area_code_mobile', 10)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('skype', 50)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('diveramkt_miscelanious_companies');
    }
}
