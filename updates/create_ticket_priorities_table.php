<?php namespace SpAnjaan\Support\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Winter\Storm\Database\Updates\Migration;

/**
 * Class CreateTicketPrioritiesTable
 * @package SpAnjaan\Support\Updates
 */
class CreateTicketPrioritiesTable extends Migration
{

    public function up()
    {
        Schema::create('spanjaan_support_ticket_priorities', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_support_ticket_priorities');
    }

}
