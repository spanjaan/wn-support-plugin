<?php namespace SpAnjaan\Support\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Winter\Storm\Database\Updates\Migration;

/**
 * Class CreateTicketAttachmentsTable
 * @package SpAnjaan\Support\Updates
 */
class CreateTicketAttachmentsTable extends Migration
{

    public function up()
    {
        Schema::create(
            'spanjaan_support_ticket_attachments',
            function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('file_name');
                $table->string('file_path');
                $table->integer('file_size');
                $table->string('content_type');
                $table->integer('ticket_id');
                $table->integer('user_id')->unsigned()->nullable()->index();
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('spanjaan_support_ticket_attachments');
    }

}

