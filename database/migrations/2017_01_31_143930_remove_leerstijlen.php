<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveLeerstijlen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thema', function(Blueprint $table)
        {
            $table->dropForeign('fk_thema_ervaren');
            $table->dropForeign('fk_thema_reflecteren');
            $table->dropForeign('fk_thema_conceptualiseren');
            $table->dropForeign('fk_thema_toepassen');
            $table->dropIndex('fk_thema_toepassen');

            $table->dropColumn('ervaren_id');
            $table->dropColumn('reflecteren_id');
            $table->dropColumn('conceptualiseren_id');
            $table->dropColumn('toepassen_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('thema', function(Blueprint $table)
        {
            $table->integer('ervaren_id')->nullable()->index('fk_ervaren_idx');
            $table->integer('reflecteren_id')->nullable()->index('fk_reflecteren_idx');
            $table->integer('conceptualiseren_id')->nullable()->index('fk_conceptualiseren_idx');
            $table->integer('toepassen_id')->nullable()->index('fk_toepassen_idx');
        });
    }
}
