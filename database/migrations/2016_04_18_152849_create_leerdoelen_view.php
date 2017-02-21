<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class CreateLeerdoelenView extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        DB::statement("
            CREATE VIEW leerdoelenView AS
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
        DB::statement("DROP VIEW leerdoelenView");
    }
}
