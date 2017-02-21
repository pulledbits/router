<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddForeignKeysToStudentgroepcontactTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('studentgroepcontact', function(Blueprint $table)
		{
			$table->foreign('contactmoment_id', 'contactmoment')->references('id')->on('les')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('studentgroep_id', 'studentgroep')->references('id')->on('studentgroep')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		Schema::table('studentgroepcontact', function(Blueprint $table)
		{
			$table->dropForeign('contactmoment');
			$table->dropForeign('studentgroep');
		});
	}

}
