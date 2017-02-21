<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class AlterTableContactmomentAddEventUID extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
		Schema::table('contactmoment', function(Blueprint $table)
		{
		    $table->text('ical_uid')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
		Schema::table('contactmoment', function(Blueprint $table)
		{
		    $table->dropColumn('ical_uid');
		});
    }
}
