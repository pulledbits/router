<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class CreateTableLesweek extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
		$table = $schema->createTable('lesweek');
        $table->string('jaar', 4);
        $table->string('kalenderweek', 2);
        $table->string('onderwijsweek', 2)->nullable();
        $table->string('blokweek', 2)->nullable();
        $table->primary(['jaar', 'kalenderweek'], 'weekjaar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
		$schema->dropTable('lesweek');
    }
}
