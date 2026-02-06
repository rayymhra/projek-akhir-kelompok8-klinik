<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->integer('nomor_antrian')->nullable()->after('status');
            $table->string('prefix_antrian', 10)->nullable()->after('nomor_antrian');
            $table->timestamp('waktu_dipanggil')->nullable()->after('nomor_antrian');
            $table->timestamp('waktu_selesai')->nullable()->after('waktu_dipanggil');
            $table->string('poli', 50)->nullable()->after('waktu_selesai');
            $table->enum('prioritas', ['normal', 'prioritas'])->default('normal')->after('poli');
        });
    }

    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['nomor_antrian', 'prefix_antrian', 'waktu_dipanggil', 'waktu_selesai', 'poli', 'prioritas']);
        });
    }
};