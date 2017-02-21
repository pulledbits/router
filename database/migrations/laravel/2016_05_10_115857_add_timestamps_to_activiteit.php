<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class AddTimestampsToActiviteit extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
		Schema::table('activiteit', function(Blueprint $table)
		{
		    $table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
		Schema::table('activiteit', function(Blueprint $table)
		{
		    $table->dropTimestamps();
		});
    }
}
