<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameContactmomentenVandaagToContactmomentVandaag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    public function down()
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
