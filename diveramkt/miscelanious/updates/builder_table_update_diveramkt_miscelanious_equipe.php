<?php namespace Diveramkt\Miscelanious\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDiveramktMiscelaniousEquipe extends Migration
{
    public function up()
{
    Schema::table('diveramkt_miscelanious_equipe', function($table)
    {
        $table->string('slug', 255)->nullable();
        $table->text('infos')->nullable();
    });
}

public function down()
{
    Schema::table('diveramkt_miscelanious_equipe', function($table)
    {
        $table->dropColumn('slug');
        $table->dropColumn('infos');
    });
}
}
