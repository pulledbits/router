<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class AddColumnJaarKalenderweekToLesTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		Schema::table('les', function(Blueprint $table)
		{
			$table->string('jaar', 4);
			$table->string('kalenderweek', 2);
		});
		
		DB::statement("
		    UPDATE les
		    SET 
		      les.jaar = '2016', 
		      les.kalenderweek = '1'
		");
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
			$table->dropColumn('jaar');
			$table->dropColumn('kalenderweek');
		});
	}

}
