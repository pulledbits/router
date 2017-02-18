<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewContactmomentImportedToday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW contactmoment_toekomst_geimporteerd_verleden AS
                SELECT *
                FROM contactmoment
                WHERE 
                    starttijd > curdate() and 
                    ical_uid is not null and 
                    (updated_at < curdate() or updated_at is null)
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW contactmoment_toekomst_geimporteerd_verleden");
    }
}
