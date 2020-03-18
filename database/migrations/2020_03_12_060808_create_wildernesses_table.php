<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWildernessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wildernesses', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->string('boundary_status');
            $table->timestamps();
        });
        Schema::table('wildernesses', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wildernesses');
    }
}
