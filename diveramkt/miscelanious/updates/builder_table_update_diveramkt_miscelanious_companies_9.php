<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousCompanies9 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->text('opening_hours')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->string('opening_hours', 255)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
