<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class CreateContactmomentRatingView extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        DB::statement("
            CREATE VIEW contactmomentrating AS
            SELECT rating.contactmoment_id, ROUND(SUM(rating.waarde)/COUNT(rating.waarde)) AS waarde FROM `rating` GROUP BY contactmoment_id");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        DB::statement("DROP VIEW IF EXISTS contactmomentrating");
    }
}
