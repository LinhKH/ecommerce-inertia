<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('country')->nullable();
            $table->string('password');
            $table->string('user_img')->nullable();
            $table->string('wishlist')->nullable();
            $table->boolean('status')->default('1');
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
        Schema::dropIfExists('users');
    }
}
