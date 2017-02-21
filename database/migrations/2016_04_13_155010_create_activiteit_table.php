<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateActiviteitTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('activiteit');
        $table->integer('id', true);
        $table->string('werkvorm', 45);
        $table->enum('organisatievorm', array('plenair','groepswerk','circuit'));
        $table->enum('werkvormsoort', array('ijsbreker','discussie','docent gecentreerd','werkopdracht','individuele werkopdracht'));
        $table->integer('tijd');
        $table->enum('intelligenties', array('VL','LM','VR','MR','LK','N','IR','IA'));
        $table->text('inhoud', 65535);
		
		$table_prefix = DB::getTablePrefix();
		DB::statement("ALTER TABLE `" . $table_prefix . "activiteit` CHANGE `intelligenties` `intelligenties` SET('VL','LM','VR','MR','LK','N','IR','IA');");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('activiteit');
	}

}
