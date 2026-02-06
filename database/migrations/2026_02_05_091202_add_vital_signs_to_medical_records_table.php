<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Add new columns for vital signs
            $table->string('tekanan_darah', 20)->nullable()->after('catatan');
            $table->string('nadi', 20)->nullable()->after('tekanan_darah');
            $table->string('suhu', 20)->nullable()->after('nadi');
            $table->string('pernafasan', 20)->nullable()->after('suhu');
            $table->text('pemeriksaan_fisik')->nullable()->after('pernafasan');
        });
    }

    public function down()
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah',
                'nadi', 
                'suhu',
                'pernafasan',
                'pemeriksaan_fisik'
            ]);
        });
    }
};