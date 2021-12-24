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
                    if($dapo->password){
                        $sedot = $this->sedot_sekolah($s, $dapo);
                        if(isset($sedot->error) && $sedot->error){
                            $sedot = $this->sedot_sekolah($s, $dapo);
                        }
                        $this->simpan_sekolah($sedot);
                    }
                }
            }
        }
    }
    private function simpan_sekolah($data){
        if(isset($sedot->error) && !$data->error){
            if($data->dapodik->status_sekolah == 2){
                Sekolah::updateOrCreate(
                    [
                        'sekolah_id' => $data->dapodik->sekolah_id,
                    ],
                    [
                        'nama' => $data->dapodik->nama,
                        'npsn' => $data->dapodik->npsn,
                        'nss' => $data->dapodik->nss,
                        'alamat_jalan' => $data->dapodik->alamat_jalan,
                        'rt' => $data->dapodik->rt,
                        'rw' => $data->dapodik->rw,
                        'nama_dusun' => $data->dapodik->nama_dusun,
                        'desa_kelurahan' => $data->dapodik->desa_kelurahan,
                        'kode_wilayah' => $data->dapodik->kode_wilayah,
                        'kecamatan' => $data->dapodik->wilayah->parrent_recursive->nama,
                        'kabupaten' => $data->dapodik->wilayah->parrent_recursive->parrent_recursive->nama,
                        'provinsi' => $data->dapodik->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                        'kecamatan_id' => $data->dapodik->wilayah->parrent_recursive->kode_wilayah,
                        'kabupaten_id' => $data->dapodik->wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                        'provinsi_id' => $data->dapodik->wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                        'kode_pos' => $data->dapodik->kode_pos,
                        'lintang' => $data->dapodik->lintang,
                        'bujur' => $data->dapodik->bujur,
                        'nomor_telepon' => $data->dapodik->nomor_telepon,
                        'nomor_fax' => $data->dapodik->nomor_fax,
                        'email' => $data->dapodik->email,
                        'website' => $data->dapodik->website,
                        'status_sekolah' => $data->dapodik->status_sekolah,
                        'kode_registrasi' => $data->dapodik->kode_registrasi,
                    ]
                );
            }
        }
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
        $response = Http::asForm()->withHeaders([
            'x-api-key' => $data_sync['sekolah_id']
        ])->withBasicAuth('admin', '1234')->post($host_server_direktorat, $data_sync);
        return $response->object();
    }
}
