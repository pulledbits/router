<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class CreatePasswordResetsTable extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('password_resets');
        $table->string('email')->index();
        $table->string('token')->index();
        $table->timestamp('created_at');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('password_resets');
    }
}
