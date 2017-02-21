<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class FixViews extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        DB::statement("DROP VIEW IF EXISTS mediaView");
        DB::statement("DROP VIEW IF EXISTS mediaview");
        DB::statement("
            CREATE VIEW mediaview AS
            SELECT DISTINCT 
                les.id as les_id,
                media.omschrijving as omschrijving
            FROM les
            LEFT JOIN thema ON thema.les_id = les.id
            LEFT JOIN activiteit ON activiteit.id IN (
                les.activerende_opening_id,
                les.focus_id,
                les.voorstellen_id,
                les.kennismaken_id,
                les.terugblik_id,
                les.huiswerk_id,
                les.evaluatie_id,
                les.pakkend_slot_id
                )
                JOIN activiteitmedia ON activiteitmedia.activiteit_id = activiteit.id
                JOIN media ON media.id = activiteitmedia.media_id
            ");


        DB::statement("DROP VIEW IF EXISTS leerdoelenview");
        DB::statement("DROP VIEW IF EXISTS leerdoelenView");
        DB::statement("
            CREATE VIEW leerdoelenview AS
                SELECT
                    thema.les_id,
                    thema.leerdoel as omschrijving
                FROM thema
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        DB::statement("DROP VIEW IF EXISTS mediaview");
    }
}
