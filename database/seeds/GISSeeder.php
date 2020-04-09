<?php

class GISSeeder extends \Illuminate\Database\Seeder
{
    public function run() {
        DB::unprepared(file_get_contents(__DIR__.'/nwagis_wildernesses.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/nwagis_geometries.sql'));
    }
}
