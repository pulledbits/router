<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddForeignKeysToContactmomentTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('contactmoment', function(Blueprint $table)
		{
			$table->foreign('les_id', 'fk_les')->references('id')->on('les')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		Schema::table('contactmoment', function(Blueprint $table)
		{
			$table->dropForeign('fk_les');
		});
	}

}
