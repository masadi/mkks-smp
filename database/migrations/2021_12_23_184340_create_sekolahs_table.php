<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->uuid('sekolah_id');
			$table->string('nama', 100);
			$table->string('npsn', 8);
			$table->string('nss', 12)->nullable();
			$table->string('alamat_jalan', 80);
            $table->decimal('rt', 2, 0)->nullable();
            $table->decimal('rw', 2, 0)->nullable();
            $table->string('nama_dusun', 60)->nullable();
			$table->string('desa_kelurahan', 60);
			$table->char('kode_wilayah', 8);
			$table->string('kecamatan')->nullable();
			$table->string('kabupaten')->nullable();
			$table->string('provinsi')->nullable();
			$table->char('kecamatan_id', 8)->nullable();
			$table->char('kabupaten_id', 8)->nullable();
			$table->char('provinsi_id', 8)->nullable();
			$table->char('kode_pos', 5)->nullable();
			$table->decimal('lintang', 18, 12)->nullable();
			$table->decimal('bujur', 18, 12)->nullable();
			$table->string('nomor_telepon', 20)->nullable();
			$table->string('nomor_fax', 20)->nullable();
			$table->string('email', 60)->nullable();
			$table->string('website', 100)->nullable();
			$table->decimal('status_sekolah', 1, 0);
            $table->bigInteger('kode_registrasi')->nullable();
			$table->string('logo_sekolah')->nullable();
            $table->string('nama_kepsek')->nullable();
            $table->string('nip_kepsek')->nullable();
            $table->string('nama_pengawas')->nullable();
            $table->string('nip_pengawas')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('sekolah_id');
            $table->index('kode_wilayah');
			$table->index('kecamatan_id');
			$table->index('kabupaten_id');
			$table->index('provinsi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sekolah');
    }
}
