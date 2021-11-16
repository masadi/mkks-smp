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
			$table->string('npsn');
			$table->string('nama');
			$table->string('nss')->nullable();
            $table->integer('bentuk_pendidikan_id');
			$table->string('alamat')->nullable();
			$table->string('desa_kelurahan')->nullable();
			$table->string('kecamatan')->nullable();
			$table->string('kode_wilayah')->nullable();
			$table->string('kabupaten')->nullable();
			$table->string('provinsi')->nullable();
			$table->string('kode_pos')->nullable();
			$table->string('lintang')->nullable();
			$table->string('bujur')->nullable();
			$table->string('no_telp')->nullable();
			$table->string('no_fax')->nullable();
			$table->string('email')->nullable();
			$table->string('website')->nullable();
            $table->integer('status_sekolah');
            $table->string('kecamatan_id', 8)->nullable();
            $table->string('kabupaten_id', 8)->nullable();
            $table->string('provinsi_id', 8)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('sekolah_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
        });
        Schema::dropIfExists('sekolah');
    }
}
