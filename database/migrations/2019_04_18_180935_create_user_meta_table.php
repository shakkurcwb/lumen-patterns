<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetaTable extends Migration
{
    public function up()
    {
        Schema::create('user_meta', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('locale', ['en_UK', 'en_CA', 'en_US', 'fr_FR', 'fr_CA', 'pt_BR', 'pt_PT', 'es_ES'])->default('en_CA');

            $table->string('theme')->nullable();

            $table->string('avatar')->nullable();

            $table->enum('gender', ['male', 'female', ''])->nullable();

            $table->dateTime('birth')->nullable();

            $table->string('nacionality')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('user_meta');
    }
}
