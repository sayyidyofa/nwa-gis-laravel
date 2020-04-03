<?php


use App\User;

class UserSeeder extends \Illuminate\Database\Seeder
{
    public function run() {
        User::create(['username'=>'admin', 'password'=>bcrypt('admin')]);
    }
}
