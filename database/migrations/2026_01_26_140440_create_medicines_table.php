<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('kode_obat')->unique();
            $table->string('jenis_obat');
            $table->string('satuan')->default('Tablet'); // ADDED
            $table->string('supplier')->nullable(); // ADDED
            $table->string('batch_number')->nullable(); // ADDED
            $table->string('lokasi_penyimpanan')->nullable(); // ADDED
            $table->string('golongan')->nullable(); // ADDED
            $table->string('kategori')->nullable(); // ADDED
            $table->text('deskripsi')->nullable(); // ADDED
            $table->integer('stok');
            $table->integer('stok_minimum')->default(10); // ADDED
            $table->decimal('harga', 10, 2);
            $table->date('expired_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};