<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('first_name', 64);
            $table->string('last_name', 64);

            $table->string('email', 128)->unique();

            $table->string('password', 255)->nullable();

            $table->boolean('is_admin')->default(0);

            $table->string('api_token', 60)->unique()->nullable()->default(null);

            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
