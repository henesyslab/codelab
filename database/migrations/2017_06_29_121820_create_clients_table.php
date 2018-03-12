<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gitlab_id')->unsigned();
            $table->enum('gitlab_api', ['groups','users'])->default('groups');
            $table->string('name');
            $table->string('path');
            $table->string('description');
            $table->text('notes');
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
        Schema::connection('sqlite')->dropIfExists('clients');
    }
}
