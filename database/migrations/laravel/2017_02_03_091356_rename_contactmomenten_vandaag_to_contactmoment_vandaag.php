<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class RenameContactmomentenVandaagToContactmomentVandaag extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        DB::statement("DROP VIEW contactmomenten_vandaag");
        DB::statement("
            CREATE VIEW contactmoment_vandaag AS
                SELECT *
                FROM contactmoment
                WHERE DATE(starttijd) = CURDATE()
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        DB::statement("DROP VIEW contactmoment_vandaag");
        DB::statement("
            CREATE VIEW contactmomenten_vandaag AS
                SELECT *
                FROM contactmoment
                WHERE DATE(starttijd) = CURDATE()
            ");
    }
}
