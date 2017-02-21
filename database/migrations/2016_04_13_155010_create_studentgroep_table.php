<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateStudentgroepTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('studentgroep');
			$table->integer('id', true);
			$table->string('naam', 5);
			$table->integer('blok_id')->index('fk_blok_idx');
			$table->unique(['naam','blok_id'], 'naam');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('studentgroep');
	}

}
