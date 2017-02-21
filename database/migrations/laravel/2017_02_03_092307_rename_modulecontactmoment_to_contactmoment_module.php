<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class RenameModulecontactmomentToContactmomentModule extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        DB::statement("DROP VIEW modulecontactmoment");
        DB::statement("
            CREATE VIEW contactmoment_module AS
                SELECT module.id AS module_id, contactmoment.*
                FROM module
                JOIN les ON les.module_id = module.id
                JOIN contactmoment ON contactmoment.les_id = les.id
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        DB::statement("DROP VIEW contactmoment_module");
        DB::statement("
            CREATE VIEW modulecontactmoment AS
                SELECT module.id AS module_id, contactmoment.*
                FROM module
                JOIN les ON les.module_id = module.id
                JOIN contactmoment ON contactmoment.les_id = les.id
            ");
    }
}
