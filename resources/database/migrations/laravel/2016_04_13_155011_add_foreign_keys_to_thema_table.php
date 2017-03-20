<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddForeignKeysToThemaTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('thema', function(Blueprint $table)
		{
			$table->foreign('conceptualiseren_id', 'fk_conceptualiseren')->references('id')->on('activiteit')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('ervaren_id', 'fk_ervaren')->references('id')->on('activiteit')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('les_id', 'fk_lesthema')->references('id')->on('les')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('reflecteren_id', 'fk_reflecteren')->references('id')->on('activiteit')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('toepassen_id', 'fk_toepassen')->references('id')->on('activiteit')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		Schema::table('thema', function(Blueprint $table)
		{
			$table->dropForeign('fk_conceptualiseren');
			$table->dropForeign('fk_ervaren');
			$table->dropForeign('fk_lesthema');
			$table->dropForeign('fk_reflecteren');
			$table->dropForeign('fk_toepassen');
		});
	}

}
