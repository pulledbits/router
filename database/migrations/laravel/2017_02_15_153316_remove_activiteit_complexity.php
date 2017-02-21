<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class RemoveActiviteitComplexity extends AbstractMigration
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
            $table->dropForeign('fk_activiteit_organisatievorm');
            $table->dropForeign('fk_activiteit_werkvormsoort');

            $table->dropColumn('organisatievorm');
            $table->dropColumn('werkvorm');
            $table->dropColumn('werkvormsoort');
            $table->dropColumn('toepassen_id');
        });

        Schema::table('activiteitintelligentie', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('activiteit_intelligentie', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('intelligentie', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('organisatievorm', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('werkvormsoort', function(Blueprint $table)
        {
            $table->drop();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        //
    }
}
