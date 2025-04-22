<?php namespace SpAnjaan\Support\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Winter\Storm\Database\Updates\Migration;

/**
 * Class CreateTicketCommentsTable
 * @package SpAnjaan\Support\Updates
 */
class CreateTicketCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('spanjaan_support_ticket_comments', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('author');
            $table->boolean('is_support')->default(false);
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_support_ticket_comments');
    }

}
