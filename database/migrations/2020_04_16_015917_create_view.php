<?php

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
        DB::statement($this->buatView());
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

    private function buatView() {
        return <<<SQL
create view `gis_view` as
select wildernesses.id as w_id, geometries.id as g_id, name, boundary_status,  geotype, coordinates, color
       from wildernesses
           left join geometries on wildernesses.id = geometries.wildernesses_id
SQL;

    }

    private function dropView(): string {
        return <<<SQL
DROP VIEW IF EXISTS `gis_view`;
SQL;

    }
}
