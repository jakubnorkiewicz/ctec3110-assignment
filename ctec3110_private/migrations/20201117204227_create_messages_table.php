<?php

use App\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{
    /**
     * Creates 'messages' table.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source_number')->nullable();
            $table->string('destination_number');
            $table->string('value');
            $table->string('bearer')->nullable();
            $table->string('message_ref')->nullable();
            $table->boolean('switch')->nullable();
            $table->string('fan_fwd_or_rvs')->nullable();
            $table->float('heater_temp')->nullable();
            $table->integer('keypad_number')->nullable();
            $table->timestamps();

            // todo get rid of the nullable(), pass the right data
        });
    }

    /**
     * Removes 'messages' table if it exists.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('messages');
    }
}
