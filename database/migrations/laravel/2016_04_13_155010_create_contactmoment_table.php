<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateContactmomentTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('contactmoment');
        $table->integer('id', true);
        $table->integer('les_id')->index('fk_les_idx');
        $table->dateTime('starttijd');
        $table->dateTime('eindtijd');
        $table->text('ruimte', 65535)->nullable();

        $table->unique('starttijd');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('contactmoment');
	}

}
