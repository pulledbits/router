<?php
namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class CreateRatingTable extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(Schema $schema)
    {
		$table = $schema->createTable('rating');
        $table->string('ipv4', 15);
        $table->integer('contactmoment_id')->index('fk_contactmomentrating_idx');
        $table->foreign('contactmoment_id')->references('id')->on('contactmoment');
        $table->enum('waarde', array('1','2','3','4','5'))->nullable();
        $table->text('inhoud', 65535);
        $table->timestamps();
        $table->softDeletes();

        $table->primary(['ipv4', 'contactmoment_id']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(Schema $schema)
    {
		$schema->dropTable('rating');
    }
}
