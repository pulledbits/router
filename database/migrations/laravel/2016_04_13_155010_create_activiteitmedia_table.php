<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateActiviteitmediaTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('activiteitmedia');
			$table->integer('activiteit_id')->index('fk_activiteitmedia_idx');
			$table->integer('media_id')->index('fk_activiteitmedia_media_idx');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('activiteitmedia');
	}

}
