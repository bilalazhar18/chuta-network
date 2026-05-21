<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_Id');
            $table->integer('receiver_Id');
            $table->float('amount');
            $table->text('description')->nullable();
            $table->text('meeting_slot');
            $table->text('status')->nullable();
            $table->text('transaction_Id')->nullable();
            $table->text('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_invoice');
    }
}
