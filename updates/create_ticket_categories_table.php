<?php namespace SpAnjaan\Support\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Winter\Storm\Database\Updates\Migration;

/**
 * Class CreateTicketCategoriesTable
 * @package SpAnjaan\Support\Updates
 */
class CreateTicketCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create(
            'spanjaan_support_ticket_categories',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_support_ticket_categories');
    }

}
