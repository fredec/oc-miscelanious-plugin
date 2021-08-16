<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Db;

class BuilderTableUpdateDiveramktMiscelaniousCompanies5 extends Migration
{
    public function up()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->string('opening_hours',255)->nullable();
            $table->integer('sort_order')->default(0);
        });
        
        DB::table('diveramkt_miscelanious_companies')->where('id',1)->update(
            array(
                'sort_order' => '1'
            )
        );
    }
    
    public function down()
    {
        Schema::table('diveramkt_miscelanious_companies', function($table)
        {
            $table->dropColumn('opening_hours');
            $table->dropColumn('sort_order');
        });
    }
}