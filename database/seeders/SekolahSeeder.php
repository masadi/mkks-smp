<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Sekolah;
use App\User;
use App\Role;
use App\Wilayah;

class SekolahSeeder extends Seeder
{
    private function get_wilayah($id_level_wilayah, $kode_wilayah, $semester_id){
        $response = Http::get('https://dapo.kemdikbud.go.id/rekap/dataSekolah', [
            'id_level_wilayah' => $id_level_wilayah,
            'kode_wilayah' => $kode_wilayah,
            'semester_id' => $semester_id,
        ]);
        return $response;
    }
    private function get_sekolah($id_level_wilayah, $kode_wilayah, $semester_id, $bentuk_pendidikan_id){
        $get_sekolah = Http::get('https://dapo.kemdikbud.go.id/rekap/progresSP', [
            'id_level_wilayah' => $id_level_wilayah,
            'kode_wilayah' => trim($kode_wilayah),
            'semester_id' => $semester_id,
            'bentuk_pendidikan_id' => $bentuk_pendidikan_id,
        ]);
        return $get_sekolah;
    }
    private function insert_sekolah($all_sekolah){
        $config = config('sekolah');
        foreach($all_sekolah as $data_sekolah){
            if($data_sekolah->status_sekolah == 'Swasta'){
                $sync_sekolah = Http::get('http://103.40.55.242/erapor_server/sync/get_sekolah/'.$data_sekolah->npsn);
                if(!$sync_sekolah->failed()){
                    $sekolah = json_decode($sync_sekolah->body());
                    if($sekolah){
                        $sekolah = $sekolah->data[0];
                        $wilayah = Wilayah::with('parrentRecursive')->withTrashed()->find($sekolah->kode_wilayah);
                        $kecamatan_id = NULL;
                        $kabupaten_id = NULL;
                        $provinsi_id = NULL;
                        $kecamatan = NULL;
                        $kabupaten = NULL;
                        $provinsi = NULL;
                        if($wilayah->parrentRecursive){
                            $kecamatan_id = $wilayah->parrentRecursive->kode_wilayah;
                            $kecamatan = $wilayah->parrentRecursive->nama;
                            if($wilayah->parrentRecursive->parrentRecursive){
                                $kabupaten_id = $wilayah->parrentRecursive->parrentRecursive->kode_wilayah;
                                $kabupaten = $wilayah->parrentRecursive->parrentRecursive->nama;
                            }
                            if($wilayah->parrentRecursive->parrentRecursive->parrentRecursive){
                                $provinsi_id = $wilayah->parrentRecursive->parrentRecursive->parrentRecursive->kode_wilayah;
                                $provinsi = $wilayah->parrentRecursive->parrentRecursive->parrentRecursive->nama;
                            }
                        }
                        Sekolah::updateOrCreate(
                            ['sekolah_id' => strtolower($sekolah->sekolah_id)],
                            [
                                'npsn' => $sekolah->npsn,
                                'nama' => $sekolah->nama,
                                'nss' => $sekolah->nss,
                                'bentuk_pendidikan_id' => $sekolah->bentuk_pendidikan_id,
                                'alamat' => $sekolah->alamat_jalan,
                                'desa_kelurahan' => $sekolah->desa_kelurahan,
                                'kecamatan' => $kecamatan,
                                'kode_wilayah' => $sekolah->kode_wilayah,
                                'kabupaten' => $kabupaten,
                                'provinsi' => $provinsi,
                                'kode_pos' => $sekolah->kode_pos,
                                'lintang' => $sekolah->lintang,
                                'bujur' => $sekolah->bujur,
                                'no_telp' => $sekolah->nomor_telepon,
                                'no_fax' => $sekolah->nomor_fax,
                                'email' => $sekolah->email,
                                'website' => $sekolah->website,
                                'status_sekolah' => $sekolah->status_sekolah,
                                'kecamatan_id' => $kecamatan_id,
                                'kabupaten_id' => $kabupaten_id,
                                'provinsi_id' => $provinsi_id,
                            ]
                        );
                        $email = ($sekolah->email) ? $sekolah->email : $sekolah->npsn.'@'.$config['domain'];
                        $user_sekolah = User::updateOrCreate(
                            ['email' => $email],
                            [
                                'sekolah_id' => $sekolah->sekolah_id,
                                'username' => $sekolah->npsn,
                                'name' => $sekolah->nama,
                                'password' => bcrypt($sekolah->npsn)
                            ]
                        );
                        if(!$user_sekolah->hasRole('sekolah')){
                            $role = Role::where('name', 'sekolah')->first();
                            $user_sekolah->attachRole($role);
                        }
                    } else {
                        $this->command->info('php artisan sedot:sekolah '.$data_sekolah->npsn);
                    }
                } else {
                    $this->command->info('php artisan sedot:sekolah '.$data_sekolah->npsn);
                }
            }
        }
    }
    public function run()
    {
        $config = config('sekolah');
        /*$response = Http::get('https://dapo.kemdikbud.go.id/rekap/dataSekolah', [
            'id_level_wilayah' => $config['id_level_wilayah'],
            'kode_wilayah' => $config['kode_wilayah'],
            'semester_id' => $config['semester_id'],
        ]);*/
        $response = $this->get_wilayah($config['id_level_wilayah'], $config['kode_wilayah'], $config['semester_id']);
        $all_data = json_decode($response->body());
        foreach($all_data as $data){
            foreach($config['bentuk_pendidikan_id'] as $bentuk_pendidikan_id){
                $get_sekolah = $this->get_sekolah($data->id_level_wilayah, trim($data->kode_wilayah), $config['semester_id'], $bentuk_pendidikan_id);
                if(!$get_sekolah->failed()){
                    $all_sekolah = json_decode($get_sekolah->body());
                    if(count($all_sekolah)){
                        $this->insert_sekolah($all_sekolah);
                    } else {
                        $get_wilayah = $this->get_wilayah($data->id_level_wilayah, trim($data->kode_wilayah), $config['semester_id']);
                        if(!$get_wilayah->failed()){
                            $all_wilayah = json_decode($get_wilayah->body());
                            if(count($all_wilayah)){
                                foreach($all_wilayah as $wilayah){
                                    $get_sekolah = $this->get_sekolah($wilayah->id_level_wilayah, trim($wilayah->kode_wilayah), $config['semester_id'], $bentuk_pendidikan_id);
                                    if(!$get_sekolah->failed()){
                                        $all_sekolah = json_decode($get_sekolah->body());
                                        $this->insert_sekolah($all_sekolah);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
