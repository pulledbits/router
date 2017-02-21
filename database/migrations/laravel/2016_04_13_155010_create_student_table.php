<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateStudentTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('student');
			$table->integer('id', true);
			$table->string('nummer', 7)->nullable()->unique('nummer');
			$table->string('roepnaam', 50)->nullable();
			$table->string('tussenvoegsel', 10)->nullable();
			$table->string('achternaam', 50)->nullable();
			$table->integer('studentgroep_id')->nullable();
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('student');
	}

}
