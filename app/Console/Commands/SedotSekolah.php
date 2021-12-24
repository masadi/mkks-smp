<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;
use App\Models\Wilayah;

class SedotSekolah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sedot:sekolah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $kabupaten = $this->sedot('https://dapo.kemdikbud.go.id/rekap/dataSekolah?id_level_wilayah=2&kode_wilayah=052600&semester_id=20211');
        foreach($kabupaten as $kab){
            //dd($kab);
            Wilayah::firstOrCreate(
                [
                    'kode_wilayah' => $kab->kode_wilayah_induk_provinsi,
                ],
                [
                    'nama' => $kab->induk_provinsi,
                    'mst_kode_wilayah' => NULL,
                    'id_level_wilayah' => 1,
                ]
            );
            Wilayah::firstOrCreate(
                [
                    'kode_wilayah' => $kab->kode_wilayah_induk_kabupaten,
                ],
                [
                    'nama' => $kab->induk_kabupaten,
                    'mst_kode_wilayah' => $kab->kode_wilayah_induk_provinsi,
                    'id_level_wilayah' => 2,
                ]
            );
            Wilayah::firstOrCreate(
                [
                    'kode_wilayah' => $kab->kode_wilayah,
                ],
                [
                    'nama' => $kab->nama,
                    'mst_kode_wilayah' => $kab->kode_wilayah_induk_kabupaten,
                    'id_level_wilayah' => $kab->id_level_wilayah,
                ]
            );
            $sekolah = $this->sedot('https://dapo.kemdikbud.go.id/rekap/progresSP?id_level_wilayah=3&kode_wilayah='.trim($kab->kode_wilayah).'&semester_id=20211&bentuk_pendidikan_id=smp');
            foreach($sekolah as $s){
                $data = $this->sedot('http://103.40.55.242/erapor_server/sync/get_sekolah/'.$s->npsn);
                foreach($data->data as $dapo){
                    if($dapo->password && $dapo->aktif && $dapo->status_sekolah == 2){
                        $this->simpan_sekolah($dapo);
                        /*
                        //dd($dapo);
                        $sedot = $this->sedot_sekolah($s, $dapo);
                        if(isset($sedot->error) && $sedot->error){
                            $sedot = $this->sedot_sekolah($s, $dapo);
                        }
                        */
                    }
                }
            }
        }
    }
    private function simpan_sekolah($data){
        Sekolah::updateOrCreate(
            [
                'sekolah_id' => $data->sekolah_id,
            ],
            [
                'nama' => $data->nama,
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
    }
    private function sedot($url){
        $response = Http::retry(3, 100)->get($url);
        return $response->object();
    }
    private function sedot_sekolah($s, $dapo){
        $data_sync = [
            'npsn' => $s->npsn,
            'username_dapo' => $dapo->email,
            'sekolah_id' => $s->sekolah_id,
            'tahun_ajaran_id' => 2021,
            'semester_id' => 20211,
            'haha' => $dapo->password,
        ];
        $sedot = $this->ambil_data('sekolah', $data_sync);
        return $sedot;
    }
    private function ambil_data($aksi, $data_sync){
        $host_server_direktorat = 'http://103.40.55.242/erapor_server/api/'.$aksi;
        $response = Http::retry(3, 100)->asForm()->withHeaders([
            'x-api-key' => $data_sync['sekolah_id']
        ])->withBasicAuth('admin', '1234')->post($host_server_direktorat, $data_sync);
        return $response->object();
    }
}
