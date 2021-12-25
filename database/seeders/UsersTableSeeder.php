<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->where('email', 'masadi.com@gmail.com')->delete();

        DB::table('users')->insert([
            'username' => 'masadi',
            'name' => 'Mas Adi',
            'email' => 'masadi.com@gmail.com',
            'password' => bcrypt('12345678'),
            'type' => 'admin',
        ]);
    }
}
