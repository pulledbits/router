<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLesModuleFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('les', function(Blueprint $table)
        {
            $table->foreign(['module_id'], 'fk_lesmodule')->references(['id'])->on('module')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('les', function(Blueprint $table)
        {
            $table->dropForeign('fk_lesmodule');
        });
    }
}
