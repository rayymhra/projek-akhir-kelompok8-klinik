<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->decimal('total_biaya', 12, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris', 'e-wallet']);
            $table->enum('status', ['menunggu', 'lunas', 'batal'])->default('menunggu');
            $table->text('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};