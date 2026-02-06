<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'total_biaya',
        'metode_pembayaran',
        'status',
        'bukti_pembayaran'
    ];

    protected $casts = [
        'total_biaya' => 'decimal:2',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}