<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThemaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thema', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('les_id')->index('fk_lesthema_idx');
			$table->string('leerdoel', 150);
			$table->integer('ervaren_id')->nullable()->index('fk_ervaren_idx');
			$table->integer('reflecteren_id')->nullable()->index('fk_reflecteren_idx');
			$table->integer('conceptualiseren_id')->nullable()->index('fk_conceptualiseren_idx');
			$table->integer('toepassen_id')->nullable()->index('fk_toepassen_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('thema');
	}

}
