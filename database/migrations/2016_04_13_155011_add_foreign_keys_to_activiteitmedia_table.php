<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddForeignKeysToActiviteitmediaTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('activiteitmedia', function(Blueprint $table)
		{
			$table->foreign('activiteit_id', 'fk_activiteitmedia')->references('id')->on('activiteit')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('media_id', 'fk_activiteitmedia_media')->references('id')->on('media')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		Schema::table('activiteitmedia', function(Blueprint $table)
		{
			$table->dropForeign('fk_activiteitmedia');
			$table->dropForeign('fk_activiteitmedia_media');
		});
	}

}
