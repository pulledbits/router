<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class RemoveEverythingNotFeedbackRelated extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
//        Schema::table('activiteit', function(Blueprint $table)
//        {
//            $table->dropForeign('fk_activiteit_organisatievorm');
//            $table->dropForeign('fk_activiteit_werkvormsoort');
//
//            $table->dropColumn('organisatievorm');
//            $table->dropColumn('werkvorm');
//            $table->dropColumn('werkvormsoort');
//            $table->dropColumn('toepassen_id');
//        });

        Schema::table('les', function(Blueprint $table)
        {

            $table->dropForeign('fk_les_activerende_opening');
            $table->dropColumn('activerende_opening_id');

            $table->dropForeign('fk_les_focus');
            $table->dropColumn('focus_id');

            $table->dropForeign('fk_les_voorstellen');
            $table->dropColumn('voorstellen_id');

            $table->dropForeign('fk_les_kennismaken');
            $table->dropColumn('kennismaken_id');

            $table->dropForeign('fk_les_terugblik');
            $table->dropColumn('terugblik_id');

            $table->dropForeign('fk_les_huiswerk');
            $table->dropColumn('huiswerk_id');

            $table->dropForeign('fk_les_evaluatie');
            $table->dropColumn('evaluatie_id');

            $table->dropForeign('fk_les_pakkend_slot');
            $table->dropColumn('pakkend_slot_id');

            $table->dropForeign('fk_les_doelgroep');
            $table->dropColumn('doelgroep_id');

        });

        Schema::table('studentgroep', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('studentverzuim', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('student', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('doelgroep', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('doelgroepervaring', function(Blueprint $table)
        {
            $table->drop();
        });


        DB::statement("DROP VIEW IF EXISTS mediaview");
        DB::statement("DROP VIEW IF EXISTS leerdoelenview");

        Schema::table('activiteitmedia', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('media', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('activiteit', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('blokgroepcontact', function(Blueprint $table)
        {
            $table->drop();
        });

        Schema::table('blokgroep', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('blok', function(Blueprint $table)
        {
            $table->drop();
        });
        Schema::table('bloknummer', function(Blueprint $table)
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
