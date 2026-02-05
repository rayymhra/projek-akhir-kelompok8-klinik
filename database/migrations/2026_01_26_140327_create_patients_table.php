<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekam_medis')->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            
            // Contact Information
            $table->string('no_hp');
            $table->string('no_hp_keluarga')->nullable();
            $table->string('email')->nullable();
            
            // Identity Information
            $table->string('nik')->nullable()->unique();
            $table->string('no_bpjs')->nullable();
            
            // Address Details
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            
            // Medical Information
            $table->string('golongan_darah', 2)->nullable();
            $table->text('alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            
            // Emergency Contact
            $table->string('nama_keluarga')->nullable();
            $table->string('hubungan_keluarga')->nullable();
            
            // Additional Information
            $table->string('pekerjaan')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->text('catatan')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};