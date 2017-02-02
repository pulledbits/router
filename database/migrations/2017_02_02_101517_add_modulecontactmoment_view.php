<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModulecontactmomentView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW modulecontactmoment AS
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
    public function down()
    {
        DB::statement("DROP VIEW modulecontactmoment");
    }
}
