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
            $table->id();
            $table->string('nom', 255)->nullable();
            $table->string('prenom', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->integer('id_organisation')->default(-1);
            $table->string('remember_token', 100)->nullable();
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();

            // Indexes

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
