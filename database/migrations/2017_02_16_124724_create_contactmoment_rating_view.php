<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactmomentRatingView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS contactmomentrating");
    }
}
