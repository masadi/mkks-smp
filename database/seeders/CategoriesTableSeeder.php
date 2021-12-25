<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            [
                'nama' => 'Informasi',
            ],
            [
                'nama' => 'Dinas',
            ],
            [
                'nama' => 'Edaran',
            ],
            [
                'nama' => 'Kegiatan',
            ],
            [
                'nama' => 'Undangan',
            ],
        ];
        foreach($kategori as $a){
            DB::table('kategori')->updateOrinsert([
                'nama' => $a
            ]);
        }
    }
}
