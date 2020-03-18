<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->createView());
        Log::notice("Please import dataset for geometries and wildernesses table into the database manually");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView() {
        return <<<SQL
create view `gis_view` as
select wildernesses_id as id, name, boundary_status,  geotype, coordinates
       from wildernesses
           join geometries on wildernesses.id = geometries.wildernesses_id
SQL;

    }

    private function dropView(): string {
        return <<<SQL
DROP VIEW IF EXISTS `gis_view`;
SQL;

    }
}
