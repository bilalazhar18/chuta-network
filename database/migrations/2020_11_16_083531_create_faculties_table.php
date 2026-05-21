<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamps();
        });

         DB::table('faculties')->insert([
            ['title' => 'Analytics and Data Science', 'slug' => 'Analytics and Data Science'],
            ['title' => 'Business', 'slug' => 'Business'],
            ['title' => 'Communications', 'slug' => 'Communications'],
            ['title' => 'Creative Intelligence and Innovation', 'slug' => 'Creative Intelligence and Innovation'],
            ['title' => 'Design, Architecture and Building', 'slug' => 'Design, Architecture and Building'],
            ['title' => 'Education', 'slug' => 'Education'],
            ['title' => 'Engineering', 'slug' => 'Engineering'],
            ['title' => 'Health', 'slug' => 'Health'],
            ['title' => 'Information Technology', 'slug' => 'Information Technology'],
            ['title' => 'International Studies', 'slug' => 'International Studies'],
            ['title' => 'Law', 'slug' => 'Law'],
            ['title' => 'Science', 'slug' => 'Science'],
            ['title' => 'Transdisciplinary Innovation', 'slug' => 'Transdisciplinary Innovation'],
             ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
    }
}
