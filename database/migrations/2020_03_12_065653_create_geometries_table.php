<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geometries', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('geotype');
            $table->longText('coordinates');
            $table->integer('wildernesses_id');
            $table->timestamps();
        });
        Schema::table('geometries', function (Blueprint $table) {
            $table->integer('id', true)->change();
            $table->foreign('wildernesses_id')
                ->references('id')
                ->on('wildernesses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geometries');
    }
}
