<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateStudentgroepcontactTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('studentgroepcontact');
			$table->integer('studentgroep_id');
			$table->integer('contactmoment_id')->index('contactmoment_idx');
			$table->primary(['studentgroep_id','contactmoment_id']);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('studentgroepcontact');
	}

}
