<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_layanan',
        'kode_layanan',
        'tarif',
        'deskripsi'
    ];

    protected $casts = [
        'tarif' => 'decimal:2'
    ];

    // Format tarif untuk display
    public function getFormattedTarifAttribute()
    {
        return 'Rp ' . number_format($this->tarif, 0, ',', '.');
    }
}