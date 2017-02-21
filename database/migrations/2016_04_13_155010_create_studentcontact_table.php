<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateStudentcontactTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('studentcontact');
			$table->integer('student_id');
			$table->integer('contactmoment_id');
			$table->primary(['student_id','contactmoment_id']);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('studentcontact');
	}

}
