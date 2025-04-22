<?php namespace SpAnjaan\Support\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Winter\Storm\Database\Updates\Migration;

/**
 * Class CreateTicketsTable
 * @package SpAnjaan\Support\Updates
 */
class CreateTicketsTable extends Migration
{

    public function up()
    {
        Schema::create(
            'spanjaan_support_tickets',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('hash_id');
                $table->integer('category_id');
                $table->integer('creator_id');
                $table->integer('user_id')->nullable();
                $table->string('email')->nullable()->index();
                $table->string('website')->nullable();
                $table->string('topic');
                $table->longText('content');
                $table->json('file_attachments')->nullable();
                $table->integer('status_id')->default(1);
                $table->integer('priority_id')->default(1);
                $table->timestamps();
            }
        );

        Schema::create(
            'spanjaan_support_ticket_ticket_comment',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->integer('ticket_id')->unsigned();
                $table->integer('comment_id')->unsigned();
                $table->primary(['ticket_id', 'comment_id']);
            }
        );
    }



    public function down()
    {
        Schema::dropIfExists('spanjaan_support_tickets');
        Schema::dropIfExists('spanjaan_support_ticket_ticket_comment');
    }

}
