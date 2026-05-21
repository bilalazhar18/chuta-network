<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('user_name');
            $table->text('slug');
            $table->integer('course_id');            
            $table->text('course_name');
            $table->text('title');
            $table->text('description');
            $table->float('price');            
            $table->text('name_of_file');
            $table->text('url_of_file');
            $table->integer('average_rating');
            $table->text('random_key');
            $table->integer('no_of_transactions');
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
        Schema::dropIfExists('resources');
    }
}
