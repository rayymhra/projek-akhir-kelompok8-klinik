<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // User yang membuat transaksi
            if (!Schema::hasColumn('transactions', 'created_by')) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('bukti_pembayaran')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // User yang mengkonfirmasi pembayaran
            if (!Schema::hasColumn('transactions', 'confirmed_by')) {
                $table->foreignId('confirmed_by')
                    ->nullable()
                    ->after('status')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // Timestamp konfirmasi
            if (!Schema::hasColumn('transactions', 'confirmed_at')) {
                $table->timestamp('confirmed_at')
                    ->nullable()
                    ->after('confirmed_by');
            }

            // User yang membatalkan transaksi
            if (!Schema::hasColumn('transactions', 'cancelled_by')) {
                $table->foreignId('cancelled_by')
                    ->nullable()
                    ->after('confirmed_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // Timestamp pembatalan
            if (!Schema::hasColumn('transactions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')
                    ->nullable()
                    ->after('cancelled_by');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['confirmed_by']);
            $table->dropForeign(['cancelled_by']);
            
            $table->dropColumn([
                'created_by',
                'confirmed_by',
                'confirmed_at',
                'cancelled_by',
                'cancelled_at'
            ]);
        });
    }
};