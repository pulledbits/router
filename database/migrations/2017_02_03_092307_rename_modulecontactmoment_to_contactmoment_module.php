<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameModulecontactmomentToContactmomentModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    public function down()
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
