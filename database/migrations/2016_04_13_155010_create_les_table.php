<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

class CreateLesTable extends AbstractMigration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(Schema $schema)
	{
		$table = $schema->createTable('les');
        $table->integer('id', true);
        $table->string('naam', 20);
        $table->integer('module_id')->index('fk_leergang_idx');
        $table->integer('activerende_opening_id')->nullable()->index('fk_activerende_opening_idx');
        $table->integer('focus_id')->nullable()->index('fk_focus_idx');
        $table->integer('voorstellen_id')->nullable()->index('fk_voorstellen_idx');
        $table->integer('kennismaken_id')->nullable()->index('fk_kennismaken_idx');
        $table->integer('terugblik_id')->nullable()->index('fk_terugblik_idx');
        $table->integer('huiswerk_id')->nullable()->index('fk_huiswerk');
        $table->integer('evaluatie_id')->nullable()->index('fk_evaluatie');
        $table->integer('pakkend_slot_id')->nullable()->index('fk_pakkend_slot');
        $table->integer('doelgroep_id')->index('fk_lesdoelgroep_idx');
        $table->text('opmerkingen', 65535)->nullable();
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(Schema $schema)
	{
		$schema->dropTable('les');
	}

}
