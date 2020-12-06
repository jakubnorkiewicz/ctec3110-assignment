<?php

use App\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceivedMessagesTable extends Migration
{
    /**
     * Creates 'received_messages' table.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('received_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source_number');
            $table->string('destination_number');
            $table->string('value');
            $table->string('bearer');
            $table->string('message_ref');
            $table->boolean('switch');
            $table->string('fan_fwd_or_rvs');
            $table->float('heater_temp');
            $table->integer('keypad_number');
            $table->timestamps();
        });
    }

    /**
     * Removes 'received_messages' table if it exists.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('received_messages');
    }
}
