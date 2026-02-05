<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'kode_obat',
        'jenis_obat',
        'satuan',
        'supplier',
        'batch_number',
        'lokasi_penyimpanan',
        'golongan',
        'kategori',
        'deskripsi',
        'stok',
        'stok_minimum',
        'harga',
        'expired_date'
    ];

    protected $casts = [
        'expired_date' => 'date'
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function reduceStock($jumlah)
    {
        $this->stok -= $jumlah;
        $this->save();
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}