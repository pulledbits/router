<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddFKLesweekToLesTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('les', function(Blueprint $table)
		{
			$table->foreign(['jaar', 'kalenderweek'], 'fk_leslesweek')->references(['jaar', 'kalenderweek'])->on('lesweek')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		Schema::table('les', function(Blueprint $table)
		{
			$table->dropForeign('fk_leslesweek');
		});
	}

}
