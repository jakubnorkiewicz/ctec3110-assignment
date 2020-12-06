<?php

use App\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSentMessagesTable extends Migration
{
    /**
     * Creates 'sent_messages' table.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('sent_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('destination_number');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Removes 'sent_messages' table if it exists.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('sent_messages');
    }
}
