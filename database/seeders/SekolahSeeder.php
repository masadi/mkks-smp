<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Models\Sekolah;
use App\Models\Wilayah;
use File;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folder = public_path('sekolah');
        $files = File::allFiles($folder);
        foreach($files as $file){
            $data = json_decode($file->getContents());
            $sedot = $this->sedot_sekolah($data);
            if(isset($sedot->error) && $sedot->error){
                $this->simpan_sekolah($dapo, 0);
            } else {
                $this->simpan_sekolah($data, 1);
            }
        }
    }
    private function simpan_sekolah($data, $status){
        echo "Status : $status \n";
        if($status){
            Sekolah::updateOrCreate(
                [
                    'sekolah_id' => $data->sekolah_id,
                ],
                [
                    'nama' => strtoupper($data->nama),
                    'npsn' => $data->npsn,
                    'nss' => $data->nss,
                    'alamat_jalan' => $data->alamat_jalan,
                    'rt' => $data->rt,
                    'rw' => $data->rw,
                    'nama_dusun' => $data->nama_dusun,
                    'desa_kelurahan' => $data->desa_kelurahan,
                    'kode_wilayah' => $data->kode_wilayah,
                    //'kecamatan' => $data->wilayah->parrent_recursive->nama,
                    //'kabupaten' => $data->wilayah->parrent_recursive->parrent_recursive->nama,
                    //'provinsi' => $data->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                    //'kecamatan_id' => $data->wilayah->parrent_recursive->kode_wilayah,
                    //'kabupaten_id' => $data->wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                    //'provinsi_id' => $data->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                    'kode_pos' => $data->kode_pos,
                    'lintang' => $data->lintang,
                    'bujur' => $data->bujur,
                    'nomor_telepon' => $data->nomor_telepon,
                    'nomor_fax' => $data->nomor_fax,
                    'email' => $data->email,
                    'website' => $data->website,
                    'status_sekolah' => $data->status_sekolah,
                    'kode_registrasi' => $data->kode_registrasi,
                ]
            );
        } else {
            $data = $data->dapodik;
            Sekolah::updateOrCreate(
                [
                    'sekolah_id' => $data->sekolah_id,
                ],
                [
                    'nama' => strtoupper($data->nama),
                    'npsn' => $data->npsn,
                    'nss' => $data->nss,
                    'alamat_jalan' => $data->alamat_jalan,
                    'rt' => $data->rt,
                    'rw' => $data->rw,
                    'nama_dusun' => $data->nama_dusun,
                    'desa_kelurahan' => $data->desa_kelurahan,
                    'kode_wilayah' => $data->kode_wilayah,
                    'kecamatan' => $data->wilayah->parrent_recursive->nama,
                    'kabupaten' => $data->wilayah->parrent_recursive->parrent_recursive->nama,
                    'provinsi' => $data->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                    'kecamatan_id' => $data->wilayah->parrent_recursive->kode_wilayah,
                    'kabupaten_id' => $data->wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                    'provinsi_id' => $data->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                    'kode_pos' => $data->kode_pos,
                    'lintang' => $data->lintang,
                    'bujur' => $data->bujur,
                    'nomor_telepon' => $data->nomor_telepon,
                    'nomor_fax' => $data->nomor_fax,
                    'email' => $data->email,
                    'website' => $data->website,
                    'status_sekolah' => $data->status_sekolah,
                    'kode_registrasi' => $data->kode_registrasi,
                    'nama_kepsek' => $data->kepala_sekolah->nama,
                    'nip_kepsek' => $data->kepala_sekolah->nip,
                ]
            );
        }
    }
    private function sedot($url){
        $response = Http::retry(3, 100)->get($url);
        return $response->object();
    }
    private function sedot_sekolah($data){
        $data_sync = [
            'npsn' => $data->npsn,
            'username_dapo' => $data->username,
            'sekolah_id' => $data->sekolah_id,
            'tahun_ajaran_id' => 2021,
            'semester_id' => 20211,
            'haha' => $data->password,
        ];
        $sedot = $this->ambil_data('sekolah', $data_sync);
        return $sedot;
    }
    private function ambil_data($aksi, $data_sync){
        //return null;
        try {
            $host_server_direktorat = 'http://103.40.55.242/erapor_server/api/'.$aksi;
            $response = Http::asForm()->withHeaders([
                'x-api-key' => $data_sync['sekolah_id']
            ])->withBasicAuth('admin', '1234')->post($host_server_direktorat, $data_sync);
            return $response->object();
        } catch(RequestException $e){
            dump($data_sync);
        }
    }
}
